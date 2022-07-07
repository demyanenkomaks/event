<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $question
 * @property int $is_active
 * @property int $order
 */
class Interview extends \yii\db\ActiveRecord
{
    const ACTIVE_NO = 0;
    const ACTIVE_YES = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview';
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
            [['name', 'question'], 'required'],
            [['question'], 'string'],
            [['is_active', 'order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['order'], 'default', 'value' => ($max = self::find()->max('`order`')) ? $max + 1 : 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Добавлена',
            'updated_at' => 'Отредактирована',
            'is_active' => 'Показывать',
            'order' => 'Порядок',
            'name' => 'Название',
            'question' => 'Вопрос',
            'image' => 'Изображение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(InterviewOption::class, ['interview_id' => 'id'])->orderBy(['order' => SORT_ASC]);
    }
}
