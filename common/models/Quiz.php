<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $name
 * @property string $description
 * @property int $is_active
 * @property int $order
 * @property int $winners_count
 *
 * @property QuizQuestion[] $quizQuestions
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz';
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
            [['name', 'winners_count'], 'required'],
            [['description'], 'string'],
            [['is_active', 'order'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['order'], 'default', 'value' => ($max = self::find()->max('`order`')) ? $max + 1 : 1],
            [['winners_count'], 'integer', 'min' => 1],
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
            'description' => 'Описание',
            'winners_count' => 'Кол-во победителей',
            'image' => 'Изображение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizQuestions()
    {
        return $this->hasMany(QuizQuestion::class, ['quiz_id' => 'id'])->orderBy(['order' => SORT_ASC]);
    }
}
