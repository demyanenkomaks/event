<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $key
 * @property string $title
 * @property string $type
 * @property string $value
 */
class Content extends \yii\db\ActiveRecord
{
    const TYPE_IMAGE = 'image';
    const TYPE_STRING = 'string';
    const TYPE_HTML = 'html';
    const TYPE_DATETIME = 'datetime';
    const TYPE_FILE = 'file';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }


    public function behaviors()
    {
        return [
            'files' => [
                'class' => \mix8872\yiiFiles\behaviors\FileAttachBehavior::class,
                'attributes' => [
                    'image' => [
                        'multiple' => false,
                        'filetypes' => ['image/*'],
                    ],
                    'file' => [
                        'multiple' => false,
                        'filetypes' => ['application/pdf', 'application/msword'],
                    ],
                ],
                'indexBy' => 'key'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'title', 'type'], 'required'],
            [['value'], 'string'],
            [['key', 'title', 'type'], 'string', 'max' => 255],
            [['key'], 'unique'],
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
            'key' => 'Ключ',
            'title' => 'Название',
            'type' => 'Тип',
            'value' => 'Значение',
            'image' => 'Изображение',
            'file' => 'Файл',
        ];
    }

    public function getValue($api = true)
    {
        switch ($this->type) {
            case self::TYPE_IMAGE:
            case self::TYPE_FILE:
                if ($file = $this->{$this->type}) {
                    $file = $api ? $_ENV['APP_DOMAIN'] . $file->url : $file->url;
                }
                $value = $file ?? null;
                break;
            case self::TYPE_STRING:
            case self::TYPE_HTML:
                $value = $this->value;
                break;
            case self::TYPE_DATETIME:
                $value = strtotime($this->value);
                break;
        }

        return $value;
    }
}
