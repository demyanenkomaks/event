<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "template_email".
 *
 * @property int $id
 * @property string $code
 * @property string $title
 * @property string $theme
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 */
class TemplateEmail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'template_email';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'title', 'theme', 'text'], 'required'],
            [['text'], 'string'],
            [['code', 'title', 'theme'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Добавлен',
            'updated_at' => 'Отредактирован',
            'code' => 'Код',
            'title' => 'Название',
            'theme' => 'Тема',
            'text' => 'Текст',
        ];
    }

    public static function listAll($keyField = 'id', $valueField = 'title', $asArray = true)
    {
        $query = static::find();
        if ($asArray) {
            $query->select([$keyField, $valueField])->asArray();
        }

        return ArrayHelper::map($query->all(), $keyField, $valueField);
    }
}
