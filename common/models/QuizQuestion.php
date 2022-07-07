<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quiz_question".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order
 * @property int $quiz_id
 * @property string $question
 * @property int $type
 * @property int $time
 * @property int $point
 * @property string $answer
 * @property string $answer_description
 *
 * @property Quiz $quiz
 * @property QuizQuestionOption[] $quizQuestionOptions
 */
class QuizQuestion extends \yii\db\ActiveRecord
{
    public const TYPE_OPTIONS = 1;
    public const TYPE_NO_OPTIONS = 2;

    public static $typesArray = [
        self::TYPE_OPTIONS => 'С вариантами',
        self::TYPE_NO_OPTIONS => 'Без вариантов',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quiz_question';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['is_active', 'order', 'quiz_id', 'type', 'time', 'point'], 'integer'],
            [['quiz_id', 'question', 'type', 'time', 'point'], 'required'],
            [['question', 'answer_description'], 'string'],
            [['answer'], 'string', 'max' => 255],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::class, 'targetAttribute' => ['quiz_id' => 'id']],
            [['order'], 'default', 'value' => ($maxOrder = GameRound::find()->max('`order`')) ? $maxOrder + 1 : 1],
            [['time', 'point'], 'integer', 'min' => 1],
            [['answer'], 'requiredAnswer', 'skipOnEmpty' => false, 'params' => []],
        ];
    }

    public function requiredAnswer($attribute, $params)
    {
        if ($this->type == self::TYPE_NO_OPTIONS && empty($this->$attribute)) {
            $field = $this->getAttributeLabel($attribute);
            $this->addError($attribute, "Необходимо заполнить «{$field}».");
        }
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
            'quiz_id' => 'Квиз',
            'question' => 'Вопрос',
            'type' => 'Тип',
            'time' => 'Время на вопрос',
            'point' => 'Балов за вопрос',
            'answer' => 'Ответ',
            'answer_description' => 'Расширенное описание ответа',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::class, ['id' => 'quiz_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizQuestionOptions()
    {
        return $this->hasMany(QuizQuestionOption::class, ['quiz_question_id' => 'id'])->orderBy(['order' => SORT_ASC]);
    }
}
