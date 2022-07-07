<?php

namespace frontend\modules\api_v1\controllers;

use common\models\Content;
use common\models\MenuMain;
use common\models\NextEvent;
use common\models\Program;
use frontend\modules\api_v1\components\ApiController;
use Yii;
use yii\filters\Cors;
use yii\filters\VerbFilter;

/**
 * Default controller for the `api_v1` module
 */
class DefaultController extends ApiController
{
    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Headers' => ['*']
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index' => ['GET'],
                'programs' => ['GET'],
                'next-events' => ['GET'],
                'main-page' => ['GET'],
            ],
        ];

        return $behaviors;
    }

    /**
     * @return \yii\web\Response
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            $status = $exception->getCode() ?: $exception->statusCode;
            $name = $exception->getName();
            $message = $exception->getMessage();

            return $this->asJson([
                'status' => $status,
                'name' => $name,
                'message' => $message
            ]);
        }

        return $this->asJson(['status' => 404]);
    }

    /**
     * @return string[]
     */
    public function actionIndex()
    {
        return [
            'status' => 200,
            'name' => 'welcome',
            'message' => 'Hi to our API'
        ];
    }

    public function actionPrograms()
    {
        return ['status' => 200, 'list' => Program::getApiList()];
    }

    public function actionNextEvents()
    {
        return ['status' => 200, 'list' => NextEvent::getApiList()];
    }

    public function actionMainPage()
    {
        $contents = Content::find()
            ->where(['key' => ['logo', 'imageKvEvent', 'titleEvent', 'descriptionEvent', 'startStringEvent', 'startEvent', 'linkIframe', 'privacyPolicy']])
            ->indexBy('key')
            ->all();

        return [
            'status' => 200,
            'header' => [
                'logo' => $contents['logo']->getValue(),
                'menu' => MenuMain::getApiHeaderList(),
            ],
            'footer' => [
                'menu' => MenuMain::getApiFooterList(),
            ],
            'event' => [
                'imageKv' => $contents['imageKvEvent']->getValue(),
                'title' => $contents['titleEvent']->getValue(),
                'description' => $contents['descriptionEvent']->getValue(),
                'startString' => $contents['startStringEvent']->getValue(),
                'start' => $contents['startEvent']->getValue(),
                'linkIframe' => $contents['linkIframe']->getValue(),
            ],
            'settings' => [
                'resetPassword' => !!Yii::$app->config->resetPassword,
                'privacyPolicy' => $contents['privacyPolicy']->getValue(),
            ],
        ];
    }
}
