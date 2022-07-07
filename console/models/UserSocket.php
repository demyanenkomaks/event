<?php

namespace console\models;

use common\models\User;
use yii\redis\ActiveRecord;

class UserSocket extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'resourceId', 'user_id', 'connection', 'app'];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
