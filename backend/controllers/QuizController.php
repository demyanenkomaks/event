<?php

namespace backend\controllers;

use backend\models\QuizQuestionSearch;
use common\classes\SortableGridAction;
use common\models\Model;
use common\models\QuizQuestion;
use common\models\QuizQuestionOption;
use Yii;
use common\models\Quiz;
use backend\models\QuizSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuizController implements the CRUD actions for Quiz model.
 */
class QuizController extends Controller
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
                    'delete-question' => ['POST'],
                    'sort-question' => ['POST'],
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
                'modelName' => Quiz::class,
            ],
            'sort-question' => [
                'class' => SortableGridAction::class,
                'modelName' => QuizQuestion::class,
            ],
        ];
    }

    /**
     * Lists all Quiz models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuizSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['order' => SORT_ASC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Quiz model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Quiz();
        $model->order = ($maxOrder = Quiz::find()->max('`order`')) ? $maxOrder + 1 : 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Успешно добавлено');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Quiz model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $searchModel = new QuizQuestionSearch();
        $searchModel->quiz_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['order' => SORT_ASC];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Успешно отредактировано');
            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Quiz model.
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
     * Finds the Quiz model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quiz the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quiz::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    protected function findModelQuestion($id)
    {
        if (($model = QuizQuestion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionQuestionCreate($quiz) {
        $quiz = $this->findModel($quiz);

        $model = new QuizQuestion();
        $model->quiz_id = $quiz->id;
        $model->type = QuizQuestion::TYPE_OPTIONS;

        $modelsOptions = [new QuizQuestionOption()];

        if ($model->load(Yii::$app->request->post())) {
            if ($model->type == QuizQuestion::TYPE_OPTIONS) {
                $modelsOptions = Model::createMultiple(QuizQuestionOption::class);
                Model::loadMultiple($modelsOptions, Yii::$app->request->post());

                foreach ($modelsOptions as $index => $modelOption) {
                    $modelOption->quiz_question_id = $model->id;
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

                                if (!($flag = $modelOption->save(false))) {
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Успешно добавлено');
                            return $this->redirect(['question-update', 'id' => $model->id]);
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                }
            } elseif($model->save()) {
                Yii::$app->session->setFlash('success', 'Успешно добавлено');
                return $this->redirect(['question-update', 'id' => $model->id]);
            }
        }

        return $this->render('question/create', [
            'model' => $model,
            'modelsOptions' => (empty($modelsOptions)) ? [new QuizQuestionOption] : $modelsOptions,
        ]);
    }

    public function actionQuestionUpdate($id) {
        $model = $this->findModelQuestion($id);
        $modelsOptions = $model->quizQuestionOptions;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->type == QuizQuestion::TYPE_OPTIONS) {
                $oldOptionsIDs = ArrayHelper::map($modelsOptions, 'id', 'id');
                $modelsOptions = Model::createMultiple(QuizQuestionOption::class, $modelsOptions);
                Model::loadMultiple($modelsOptions, Yii::$app->request->post());
                $deletedOptionsIDs = array_diff($oldOptionsIDs, array_filter(ArrayHelper::map($modelsOptions, 'id', 'id')));

                foreach ($modelsOptions as $index => $modelOption) {
                    $modelOption->quiz_question_id = $model->id;
                    $modelOption->order = $index;
                }

                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsOptions) && $valid;

                if ($valid) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedOptionsIDs)) {
                                QuizQuestionOption::deleteAll(['id' => $deletedOptionsIDs]);
                            }

                            foreach ($modelsOptions as $modelOption) {
                                if ($flag === false) {
                                    break;
                                }

                                if (!($flag = $modelOption->save(false))) {
                                    break;
                                }
                            }
                        }

                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Успешно отредактировано');
                            return $this->redirect(['question-update', 'id' => $model->id]);
                        } else {
                            $transaction->rollBack();
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                    }
                }
            } elseif($model->save()) {
                QuizQuestionOption::deleteAll(['quiz_question_id' => $model->id]);

                Yii::$app->session->setFlash('success', 'Успешно отредактировано');
                return $this->redirect(['question-update', 'id' => $model->id]);
            }
        }

        return $this->render('question/update', [
            'model' => $model,
            'modelsOptions' => (empty($modelsOptions)) ? [new QuizQuestionOption] : $modelsOptions,
        ]);
    }

    public function actionQuestionDelete($id)
    {
        $model = $this->findModelQuestion($id);
        $quiz = $model->quiz_id;
        $model->delete();
        Yii::$app->session->setFlash('success', 'Успешно удалено');

        return $this->redirect(['update', 'id' => $quiz]);
    }
}
