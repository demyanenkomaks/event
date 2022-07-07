<?php
namespace backend\controllers;

use common\models\Content;
use Yii;
use yii\base\Model;
use yii\web\Controller;

/**
 * Content controller
 */
class ContentController extends Controller
{
    public function actionIndex()
    {
        $models = Content::find()
            ->where(['key' => ['logo', 'imageKvEvent', 'titleEvent', 'descriptionEvent', 'startStringEvent', 'startEvent', 'linkIframe',
                'privacyPolicy', 'favicon', 'titleMeta', 'descriptionMeta', 'imageMeta']])
            ->indexBy('key')
            ->all();

        if (Yii::$app->request->isPost && $post = Yii::$app->request->post()) {
            $saved = null;

            if (Model::loadMultiple($models, $post)) {
                foreach ($models as $model) {
                    if(!$saved = $model->save()) {
                        break;
                    }
                }
            }

            if ($saved) {
                Yii::$app->session->setFlash('success', 'Данные сохранены');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка сохранения данных!');
            }
        }

        return $this->render('index', [
            'models' => $models
        ]);
    }

}
