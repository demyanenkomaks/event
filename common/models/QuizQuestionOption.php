<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz_question_option".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $quiz_question_id
 * @property string $option
 * @property int $is_active
 * @property int $order
 * @property int $is_correct
 *
 * @property QuizQuestion $quizQuestion
 */
class QuizQuestionOption extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_question_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['quiz_question_id'], 'required'],
            [['quiz_question_id', 'is_active', 'order', 'is_correct'], 'integer'],
            [['option'], 'string', 'max' => 255],
            [['quiz_question_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuizQuestion::class, 'targetAttribute' => ['quiz_question_id' => 'id']],
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
            'quiz_question_id' => 'Вопрос квиза',
            'option' => 'Вариант',
            'is_correct' => 'Правильный',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizQuestion()
    {
        return $this->hasOne(QuizQuestion::class, ['id' => 'quiz_question_id']);
    }
}
