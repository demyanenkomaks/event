<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "speaker".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property string $fio
 * @property string $description
 */
class Speaker extends \yii\db\ActiveRecord
{
    const ACTIVE_NO = 0;
    const ACTIVE_YES = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'speaker';
    }

    public function behaviors()
    {
        return [
            'files' => [
                'class' => \mix8872\yiiFiles\behaviors\FileAttachBehavior::class,
                'attributes' => [
                    'photo' => [
                        'multiple' => false,
                        'filetypes' => ['image/*'],
                    ],
                ],
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
            [['is_active'], 'integer'],
            [['fio'], 'required'],
            [['fio', 'description'], 'string'],
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
            'is_active' => 'Показывать',
            'fio' => 'ФИО',
            'description' => 'Описание (регалии)',
            'photo' => 'Фото'
        ];
    }

    public static function getList() {
        return ArrayHelper::map(self::find()->where(['is_active' => self::ACTIVE_YES])->all(), 'id', 'fio');
    }
}
