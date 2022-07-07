<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "program".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order
 * @property string $time_from
 * @property string $time_before
 * @property string $name
 * @property array $speakerArray
 */
class Program extends \yii\db\ActiveRecord
{
    const ACTIVE_NO = 0;
    const ACTIVE_YES = 1;

    public $speakerArray;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'time_from', 'time_before'], 'safe'],
            [['is_active', 'order'], 'integer'],
            [['order', 'time_from', 'name'], 'required'],
            [['name'], 'string'],
            [['speakerArray'], 'speakerArrayValidate']
        ];
    }

    public function speakerArrayValidate($attribute, $params)
    {
        foreach ($this->speakerArray as $item) {
            if (!Speaker::find()->where(['id' => trim($item)])->exists()) {
                $this->addError($attribute, "Спикер «{$item}» не найден.");
                break;
            }
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
            'time_from' => 'Время от',
            'time_before' => 'Время до',
            'name' => 'Название',
            'speakerArray' => 'Спикеры'
        ];
    }

    public function afterFind()
    {
        $this->speakerArray = ArrayHelper::getColumn($this->programSpeaker, 'speaker_id');

        parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        Yii::$app->db->createCommand()->delete(ProgramSpeaker::tableName(), ['program_id' => $this->id])->execute();
        if (!empty($this->speakerArray)) {
            foreach ($this->speakerArray as $item) {
                $model = new ProgramSpeaker();
                $model->program_id = $this->id;
                $model->speaker_id = $item;
                $model->save(false);
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProgramSpeaker()
    {
        return $this->hasMany(ProgramSpeaker::class, ['program_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpeaker()
    {
        return $this->hasMany(Speaker::class, ['id' => 'speaker_id'])->where(['is_active' => Speaker::ACTIVE_YES])
            ->via('programSpeaker');
    }

    public static function getApiList()
    {
        if ($models = Program::find()->with(['speaker'])->where(['is_active' => Program::ACTIVE_YES])->orderBy(['order' => SORT_ASC])->all()) {
            $list = [];

            foreach ($models as $model) {
                $speakers = [];

                foreach ($model->speaker as $item) {
                    if ($photo = $item->photo) {
                        $photo = $_ENV['APP_DOMAIN'] . $photo->url;
                    }
                    $speakers[] = [
                        'photo' => $photo,
                        'fio' => $item->fio,
                        'description' => $item->description,
                    ];
                }

                $list[] = [
                    'timeFrom' => $model->time_from ? Yii::$app->formatter->asTime($model->time_from) : null,
                    'timeBefore' => $model->time_before ? Yii::$app->formatter->asTime($model->time_before) : null,
                    'name' => $model->name,
                    'speakers' => $speakers ?? null,
                ];
            }
        }

        return $list ?? null;
    }
}
