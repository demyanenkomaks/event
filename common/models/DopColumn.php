<?php
namespace common\models;

use yii\base\Model;

class DopColumn extends Model
{
    public $value;
    public $key;
    public $required;
    public $title;
    public $type;
    public $selectList;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['key', 'required', 'title', 'type', 'selectList'], 'safe']
        ];
    }
}
