<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "next_event".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order
 * @property string $name
 * @property string $when
 * @property string $link
 */
class NextEvent extends \yii\db\ActiveRecord
{
    const ACTIVE_NO = 0;
    const ACTIVE_YES = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'next_event';
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
            [['is_active', 'order'], 'integer'],
            [['order', 'name', 'when'], 'required'],
            [['name'], 'string'],
            [['when', 'link'], 'string', 'max' => 255],
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
            'when' => 'Когда',
            'link' => 'Ссылка',
            'image' => 'Изображение'
        ];
    }

    public static function getApiList()
    {
        if ($models = NextEvent::find()->where(['is_active' => Program::ACTIVE_YES])->orderBy(['order' => SORT_ASC])->all()) {
            $list = [];

            foreach ($models as $model) {
                if ($image = $model->image) {
                    $image = $_ENV['APP_DOMAIN'] . $image->url;
                }
                $list[] = [
                    'image' => $image,
                    'name' => $model->name,
                    'when' => $model->when,
                    'link' => $model->link,
                ];
            }
        }

        return $list ?? null;
    }
}
