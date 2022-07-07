<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_round".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order
 * @property int $game_id
 * @property int $time
 * @property string $answer
 * @property string $answer_description
 *
 * @property Game $game
 */
class GameRound extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_round';
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
            [['is_active', 'order', 'game_id', 'time'], 'integer'],
            [['game_id', 'time', 'answer', 'answer_description'], 'required'],
            [['answer_description'], 'string'],
            [['answer'], 'string', 'max' => 255],
            [['game_id'], 'exist', 'skipOnError' => true, 'targetClass' => Game::class, 'targetAttribute' => ['game_id' => 'id']],
            [['order'], 'default', 'value' => ($maxOrder = GameRound::find()->max('`order`')) ? $maxOrder + 1 : 1],
            [['time'], 'integer', 'min' => 1],
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
            'game_id' => 'Game ID',
            'time' => 'Время на вопрос',
            'answer' => 'Ответ',
            'answer_description' => 'Расширенное описание ответа',
            'image' => 'Изображение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGame()
    {
        return $this->hasOne(Game::class, ['id' => 'game_id']);
    }
}
