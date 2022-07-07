<?php

namespace backend\controllers;

use common\models\DopColumn;
use common\models\Model;
use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelFile = new User();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelFile' => $modelFile,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => 'create']);
        $dopColumns = [];

        if (!empty($model->configDopColumn)) {
            foreach ($model->configDopColumn->dopColumn as $key => $column) {
                $modelDopColumn = new DopColumn(['value' => null]);
                $modelDopColumn->attributes = $column;
                $selectList = array_map('trim', explode(',', $column['select_list']));
                $modelDopColumn->selectList = array_combine($selectList, $selectList);

                $dopColumns[] = $modelDopColumn;
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $dopColumns = Model::createMultiple(DopColumn::class, $dopColumns, 'key');
            Model::loadMultiple($dopColumns, Yii::$app->request->post());

            if ($model->validate() && Model::validateMultiple($dopColumns)) {
                if (!empty($dopColumns)) {
                    foreach ($dopColumns as $column) {
                        $key = $column->key;
                        $model->dop[$key] = $column->value;
                    }
                }

                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', "Пользователь успешно добавлен");
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'dopColumns' => $dopColumns,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $dopColumns = [];

        if (!empty($model->configDopColumn)) {
            foreach ($model->configDopColumn->dopColumn as $key => $column) {
                $value = array_key_exists($key, $model->dop) ? $model->dop[$key] : null;

                $modelDopColumn = new DopColumn(['value' => $value]);
                $modelDopColumn->attributes = $column;
                $selectList = array_map('trim', explode(',', $column['select_list']));
                $modelDopColumn->selectList = array_combine($selectList, $selectList);

                $dopColumns[] = $modelDopColumn;
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $dopColumns = Model::createMultiple(DopColumn::class, $dopColumns, 'key');
            Model::loadMultiple($dopColumns, Yii::$app->request->post());

            if ($model->validate() && Model::validateMultiple($dopColumns)) {
                if (!empty($dopColumns)) {
                    foreach ($dopColumns as $column) {
                        $key = $column->key;
                        $model->dop[$key] = $column->value;
                    }
                }

                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', "Пользователь успешно отредактирован");
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'dopColumns' => $dopColumns,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = explode(',', $id);
        foreach (User::findAll(['id' => $id]) as $user) {
            $user->delete();
        }

        Yii::$app->getSession()->setFlash('success', "Удалено пользователей: " . count($id));

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Assign or revoke assignment to user
     *
     * @return mixed
     */
    public function actionAssign()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];
        $manager = Yii::$app->authManager;
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $name) {
                try {
                    $item = $manager->getRole($name);
                    $item = $item ?: $manager->getPermission($name);
                    $manager->assign($item, $id);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        } else {
            foreach ($roles as $name) {
                try {
                    $item = $manager->getRole($name);
                    $item = $item ?: $manager->getPermission($name);
                    $manager->revoke($item, $id);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                }
            }
        }
        Yii::$app->cache->flush('rbac');
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'type' => 'S',
            'errors' => $error,
        ];
    }

    /**
     * Search roles of user
     * @param  integer $id
     * @param  string $target
     * @param  string $term
     * @return array
     */
    public function actionRoleSearch($id, $target, $term = '')
    {
        Yii::$app->response->format = 'json';
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
        $permissions = $authManager->getPermissions();

        $avaliable = [];
        $assigned = [];
        foreach ($authManager->getAssignments($id) as $assigment) {
            if (isset($roles[$assigment->roleName])) {
                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
                    $assigned['Roles'][$assigment->roleName] = $assigment->roleName;
                }
                unset($roles[$assigment->roleName]);
            } elseif (isset($permissions[$assigment->roleName]) && $assigment->roleName[0] != '/') {
                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
                    $assigned['Permissions'][$assigment->roleName] = $assigment->roleName;
                }
                unset($permissions[$assigment->roleName]);
            }
        }

        if ($target == 'avaliable') {
            if (count($roles)) {
                foreach ($roles as $role) {
                    if (empty($term) || strpos($role->name, $term) !== false) {
                        $avaliable['Roles'][$role->name] = $role->name;
                    }
                }
            }
            if (count($permissions)) {
                foreach ($permissions as $role) {
                    if ($role->name[0] != '/' && (empty($term) || strpos($role->name, $term) !== false)) {
                        $avaliable['Permissions'][$role->name] = $role->name;
                    }
                }
            }
            return $avaliable;
        } else {
            return $assigned;
        }
    }

    public function actionImport()
    {
        ini_set("max_execution_time", "3600");

        $file = UploadedFile::getInstance((new User()), 'file');

        if ($file && $handle = fopen($file->tempName, 'r')) {
            if (fgets($handle, 4) !== "\xef\xbb\xbf") rewind($handle);

            $result  = User::import($handle);
            fclose($handle);
            if ($result['success']) {
                Yii::$app->session->setFlash('success', "Импортировано {$result['count']} пользователей.");
            } else {
                $errors = [];
                foreach ($result['errors'] as $i => $error) {
                    $err = array_shift($error);
                    $errors[] = "Строка {$i}: " . array_shift($err);
                }
                Yii::$app->session->setFlash('error', "Ошибка импорта. Импортировано {$result['count']} пользователей. " .implode(', ', $errors));
            }
            return $this->redirect(['index']);
        }
    }
}
