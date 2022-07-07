<?php

namespace frontend\modules\api_v1;

use Yii;

/**
 * api_v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\api_v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'frontend\modules\api_v1\models\Token',
            'enableAutoLogin' => false,
        ]);

        Yii::$app->errorHandler->errorAction = 'v1/default/error';
    }
}
