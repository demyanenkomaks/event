<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "interview_option".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order
 * @property int $interview_id
 * @property string $option
 *
 * @property Interview $interview
 */
class InterviewOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interview_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['is_active', 'order', 'interview_id'], 'integer'],
            [['order', 'interview_id', 'option'], 'required'],
            [['option'], 'string'],
            [['interview_id'], 'exist', 'skipOnError' => true, 'targetClass' => Interview::class, 'targetAttribute' => ['interview_id' => 'id']],
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
            'interview_id' => 'Опрос',
            'option' => 'Вариант',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['id' => 'interview_id']);
    }
}
