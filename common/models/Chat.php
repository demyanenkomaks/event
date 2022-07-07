<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $user_id
 * @property string $message
 * @property int $status
 * @property int $moderator_id
 * @property int $message_id
 * @property int $speaker_id
 *
 * @property Chat $response
 * @property Chat $answerTo
 * @property User $moderator
 * @property Speaker $speaker
 * @property User $user
 */
class Chat extends \yii\db\ActiveRecord
{
    public const STATUS_NEW = 0;
    public const STATUS_IN_WORK = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_REJECTED = 3;

    public static $statusArray = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_APPROVED => 'Одобрено',
        self::STATUS_REJECTED => 'Отклонено',
    ];

    public static $statusColors = [
        self::STATUS_NEW => 'info',
        self::STATUS_IN_WORK => 'primary',
        self::STATUS_APPROVED => 'success',
        self::STATUS_REJECTED => 'danger',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'message'], 'required'],
            [['user_id', 'status', 'moderator_id', 'message_id', 'speaker_id'], 'integer'],
            [['message'], 'string'],
            [['message_id'], 'exist', 'skipOnError' => true, 'targetClass' => Chat::class, 'targetAttribute' => ['message_id' => 'id']],
            [['moderator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['moderator_id' => 'id']],
            [['speaker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Speaker::class, 'targetAttribute' => ['speaker_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['status'], 'default', 'value' => self::STATUS_NEW],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Добавлено',
            'updated_at' => 'Отредактировано',
            'user_id' => 'Пользователь',
            'message' => 'Сообщение',
            'status' => 'Статус',
            'moderator_id' => 'Модератор',
            'message_id' => 'Ответ на сообщение',
            'speaker_id' => 'Спикер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswerTo()
    {
        return $this->hasOne(Chat::class, ['id' => 'message_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponse()
    {
        return $this->hasOne(Chat::class, ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModerator()
    {
        return $this->hasOne(User::class, ['id' => 'moderator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpeaker()
    {
        return $this->hasOne(Speaker::class, ['id' => 'speaker_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function formationData()
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'dateTime' => strtotime($this->created_at),
            'date' => Yii::$app->formatter->asDate($this->created_at),
            'time' => Yii::$app->formatter->asTime($this->created_at),
            'user' => [
                'id' => $this->user->id,
                'signature' => !!$this->user->is_moderator ? 'Модератор' : $this->user->signature,
                'isModerator' => !!$this->user->is_moderator,
            ],
            'answerTo' => !!$this->answerTo ? [
                'message' => $this->answerTo->formationData(),
            ] : null,
        ];
    }
}
