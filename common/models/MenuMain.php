<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "menu_main".
 *
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_active
 * @property int $order
 * @property string $menu
 * @property string $name
 * @property string $link
 * @property int $targetBlank
 * @property int $type
 */
class MenuMain extends \yii\db\ActiveRecord
{
    const ACTIVE_NO = 0;
    const ACTIVE_YES = 1;

    const TYPE_LINK = 0;
    const TYPE_PROGRAM = 1;
    const TYPE_NEXT_EVENT = 2;

    public static $typeAll = [
        self::TYPE_LINK => 'link',
        self::TYPE_PROGRAM => 'program',
        self::TYPE_NEXT_EVENT => 'nextEvent',
    ];

    public static $typeList = [
        self::TYPE_LINK => 'Ссылка',
        self::TYPE_PROGRAM => 'Программа',
        self::TYPE_NEXT_EVENT => 'Следующие мероприятия',
    ];

    const MENU_HEADER = 'h';
    const MENU_FOOTER = 'f';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_main';
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
            [['order', 'name'], 'required'],
            [['is_active', 'order', 'targetBlank', 'type'], 'integer'],
            [['menu'], 'string'],
            [['name', 'link'], 'string', 'max' => 255],
            [['link'], 'validateLink', 'skipOnEmpty'=> false],
        ];
    }

    public function validateLink($attribute, $params)
    {
        if ($this->type == 0 && empty($this->link)) {
            $this->addError($attribute, "Необходимо заполнить «{$this->getAttributeLabel('link')}».");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Добавлен',
            'updated_at' => 'Отредактирован',
            'is_active' => 'Показывать',
            'order' => 'Порядок',
            'menu' => 'Меню',
            'name' => 'Название',
            'link' => 'Ссылка',
            'targetBlank' => 'Открывать в новой вкладке',
            'type' => 'Тип',
            'image' => 'Изображение'
        ];
    }

    public static function getApiHeaderList()
    {
        if ($models = self::find()->where(['is_active' => self::ACTIVE_YES, 'menu' => self::MENU_HEADER])->orderBy(['order' => SORT_ASC])->all()) {
            $list = [];

            foreach ($models as $model) {
                $name = $model->name;

                if ($model->type === self::TYPE_LINK) {
                    $item = [
                        'name' => $name,
                        'link' => $model->link,
                        'targetBlank' => !!$model->targetBlank,
                    ];
                } elseif($type = self::$typeAll[$model->type]) {
                    $item = [
                        'name' => $name,
                        'type' => $type,
                    ];
                }

                $list[] = $item;
            }
        }

        return $list ?? null;
    }

    public static function getApiFooterList()
    {
        if ($models = self::find()->where(['is_active' => self::ACTIVE_YES, 'menu' => self::MENU_FOOTER])->orderBy(['order' => SORT_ASC])->all()) {
            $list = [];

            foreach ($models as $model) {
                $name = $model->name;
                if ($image = $model->image) {
                    $image = $_ENV['APP_DOMAIN'] . $image->url;
                }

                if ($model->type === self::TYPE_LINK) {
                    $item = [
                        'name' => $name,
                        'link' => $model->link,
                        'targetBlank' => !!$model->targetBlank,
                        'image' => $image,
                    ];
                } elseif($type = self::$typeAll[$model->type]) {
                    $item = [
                        'name' => $name,
                        'type' => $type,
                        'image' => $image,
                    ];
                }

                $list[] = $item;
            }
        }

        return $list ?? null;
    }
}
