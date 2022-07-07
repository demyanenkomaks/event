<?php

namespace common\components;

use common\models\User;
use common\models\UserDopColumn;
use Yii;

class ConfigUserDopColumn
{
    public $check = true;

    public $configColumn = null;
    public $dopColumn = [];
    public $configSignature;
    public $stringSignature;

    function __construct()
    {
        if ($this->check) {
            $this->check = false;

            $this->configColumn = UserDopColumn::find()->asArray()->all();

            foreach ($this->configColumn as $value) {
                $key = $value['key'];
                if (!in_array($key, User::$defaultDbUserColumn)) {
                    $this->dopColumn[$key] = $value;
                }
            }

            if ($this->stringSignature = Yii::$app->config->userSignature) {
                $pattern = '/\{((\w)([^}]+))}/';
                preg_match_all($pattern, $this->stringSignature, $array);

                foreach ($array[1] as $item) {
                    $column = explode(':', $item);
                    $this->configSignature[] = [
                        'key' => $item,
                        'name' => $column[0],
                        'count' => isset($column[1]) ? (int)$column[1] : null,
                    ];
                }
            }
        }
    }
}
