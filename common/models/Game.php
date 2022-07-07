<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order
 * @property string $name
 * @property string $description
 * @property int $winners_count
 *
 * @property GameRound[] $gameRounds
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
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
            [['is_active', 'order', 'winners_count'], 'integer'],
            [['name', 'winners_count'], 'required'],
            [['description'], 'string'],
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
    public function getGameRounds()
    {
        return $this->hasMany(GameRound::class, ['game_id' => 'id'])->orderBy(['order' => SORT_ASC]);
    }
}
