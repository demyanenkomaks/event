<?php

namespace frontend\modules\api_v1\components;

use Yii;
use yii\filters\Cors;
use yii\web\Controller;

class ApiController  extends Controller
{
    public $enableCsrfValidation = false;

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

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    /**
     * {@inheritDoc}
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);

        if ($action->id === 'error') {
            $result = $result->data;
        }

        if (isset($result['status'])) {
            Yii::$app->response->setStatusCode($result['status']);
        }

        return $this->convertKeysToCamelCase($result);
    }

    /**
     * @param $array
     * @return array
     */
    private function convertKeysToCamelCase($array)
    {
        $returnArray = [];
        foreach ($array as $key => $value) {
            if (preg_match('/_/', $key)) {
                $key = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
            }

            if (is_array($value)) {
                $value = $this->convertKeysToCamelCase($value);
            }

            $returnArray[$key] = $value;
        }
        return $returnArray;
    }
}
