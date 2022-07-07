<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_dop_column".
 *
 * @property int $id
 * @property string $key
 * @property string $title
 * @property int $deleteConfig
 * @property string $type
 * @property string $select_list
 * @property int $required
 * @property int $registration
 * @property int $registration_sort
 * @property int $pars_column
 */
class UserDopColumn extends \yii\db\ActiveRecord
{
    const TYPE_STRING = 'string';
    const TYPE_EMAIL = 'email';
    const TYPE_PASSWORD = 'password';
    const TYPE_SELECT = 'select';

    public static $typeList = [
        self::TYPE_STRING => 'Строка',
        self::TYPE_EMAIL => 'Email',
        self::TYPE_PASSWORD => 'Пароль',
        self::TYPE_SELECT => 'Список',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_dop_column';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'title'], 'required'],
            [['deleteConfig', 'required', 'registration', 'registration_sort', 'pars_column'], 'integer'],
            [['type'], 'string'],
            [['key', 'title', 'select_list'], 'string', 'max' => 255],
            [['key'], 'unique'],
            [['deleteConfig'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'title' => 'Название',
            'deleteConfig' => 'Удаление поля',
            'type' => 'Тип',
            'select_list' => 'Список значений (через ",")',
            'required' => 'Обязательно к заполнению',
            'registration' => 'Вывод в форме регистрации',
            'registration_sort' => 'Порядок в форме регистрации',
            'pars_column' => 'Номер столбца для парсинга',
        ];
    }
}
