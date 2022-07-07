<?php

namespace backend\controllers;

use common\classes\SortableGridAction;
use common\models\InterviewOption;
use common\models\Model;
use Yii;
use common\models\Interview;
use backend\models\InterviewSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InterviewController implements the CRUD actions for Interview model.
 */
class InterviewController extends Controller
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
                    'sort' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'sort' => [
                'class' => SortableGridAction::class,
                'modelName' => Interview::class,
            ],
        ];
    }

    /**
     * Lists all Interview models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InterviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Interview model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Interview();
        $model->order = ($maxOrder = Interview::find()->max('`order`')) ? $maxOrder + 1 : 1;

        $modelsOptions = [new InterviewOption()];

        if ($model->load(Yii::$app->request->post())) {
            $modelsOptions = Model::createMultiple(InterviewOption::class);
            Model::loadMultiple($modelsOptions, Yii::$app->request->post());

            foreach ($modelsOptions as $index => $modelOption) {
                $modelOption->order = $index;
            }

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptions) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsOptions as $modelOption) {
                            if ($flag === false) {
                                break;
                            }
                            $modelOption->interview_id = $model->id;
                            if (!($flag = $modelOption->save(false))) {
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Успешно добавлено');
                        return $this->redirect(['update', 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsOptions' => $modelsOptions,
        ]);
    }

    /**
     * Updates an existing Interview model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsOptions = $model->options;

        if ($model->load(Yii::$app->request->post())) {
            $oldOptionsIDs = ArrayHelper::map($modelsOptions, 'id', 'id');
            $modelsOptions = Model::createMultiple(InterviewOption::class, $modelsOptions);
            Model::loadMultiple($modelsOptions, Yii::$app->request->post());
            $deletedOptionsIDs = array_diff($oldOptionsIDs, array_filter(ArrayHelper::map($modelsOptions, 'id', 'id')));

            foreach ($modelsOptions as $index => $modelOption) {
                $modelOption->order = $index;
            }

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsOptions) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedOptionsIDs)) {
                            InterviewOption::deleteAll(['id' => $deletedOptionsIDs]);
                        }
                        foreach ($modelsOptions as $modelResponse) {
                            if ($flag === false) {
                                break;
                            }
                            $modelResponse->interview_id = $model->id;
                            if (!($flag = $modelResponse->save(false))) {
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Успешно отредактировано');
                        return $this->redirect(['update', 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsOptions' => (empty($modelsOptions)) ? [new InterviewOption] : $modelsOptions,
        ]);
    }

    /**
     * Deletes an existing Interview model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Успешно удалено');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Interview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Interview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Interview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
