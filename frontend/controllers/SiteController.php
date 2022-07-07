<?php

namespace frontend\controllers;

use common\models\Content;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
//    public function actions()
//    {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//        ];
//    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dirWeb = Yii::getAlias('@webPath');
        $contents = Content::find()
            ->where(['key' => ['favicon', 'titleMeta', 'descriptionMeta', 'imageMeta']])
            ->indexBy('key')
            ->all();

        Yii::$app->view->registerLinkTag(['property' => 'og:type', 'content' => 'website']);

        $favicon = '/img/favicon/favicon.ico';
        if ($faviconNew = $contents['favicon']->getValue(false)) {
            if (file_exists($dirWeb . $faviconNew)) {
                $favicon = $faviconNew;
            }
        }
        Yii::$app->view->registerLinkTag(['rel' => 'icon', 'href' => $favicon, 'type' => mime_content_type($dirWeb . $favicon)]);

        if ($title = $contents['titleMeta']->getValue()) {
            $title = Html::encode($title);
            Yii::$app->view->title = $title;
            Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $title]);
        }

        if ($description = $contents['descriptionMeta']->getValue()) {
            $description = Html::encode($description);
            Yii::$app->view->registerMetaTag(['name' => 'description', 'content' => $description]);
            Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $description]);
        }

        if ($imageMeta = $contents['imageMeta']->getValue(false)) {
            if (file_exists($dirWeb . $imageMeta)) {
                Yii::$app->view->registerLinkTag(['property' => 'og:image', 'content' => $contents['imageMeta']->getValue()]);
            }
        }

        return $this->render('index');
    }
}
