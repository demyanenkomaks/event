<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "program_speaker".
 *
 * @property int $program_id
 * @property int $speaker_id
 *
 * @property Program $program
 * @property Speaker $speaker
 */
class ProgramSpeaker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program_speaker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['program_id', 'speaker_id'], 'required'],
            [['program_id', 'speaker_id'], 'integer'],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => Program::class, 'targetAttribute' => ['program_id' => 'id']],
            [['speaker_id'], 'exist', 'skipOnError' => true, 'targetClass' => Speaker::class, 'targetAttribute' => ['speaker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'program_id' => 'Program ID',
            'speaker_id' => 'Speaker ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(Program::class, ['id' => 'program_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpeaker()
    {
        return $this->hasOne(Speaker::class, ['id' => 'speaker_id']);
    }
}
