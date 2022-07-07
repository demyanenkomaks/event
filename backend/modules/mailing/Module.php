<?php

namespace backend\modules\mailing;

use common\models\User;
use common\models\UserDopColumn;

/**
 * mailing module definition class
 */
class Module extends \yii\base\Module
{
    public static $defaultVariablesMail = [
        '{{linkConfirmationEmail}}'   => 'Ссылка подтверждения email',
        '{{linkResetPassword}}'       => 'Ссылка сброса пароля',
    ];

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\mailing\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function getVariablesMail()
    {
        $dopColumn = [];
        if ($userDopColumn = UserDopColumn::find()->select(['key', 'title'])->asArray()->all()) {
            foreach ($userDopColumn as $item) {
                if (!array_key_exists($item['key'], User::$excludeVariables)) {
                    $key = "{{{$item['key']}}}";
                    $dopColumn[$key] = $item['title'];
                }
            }
        }

        return array_merge($dopColumn, self::$defaultVariablesMail);
    }
}
