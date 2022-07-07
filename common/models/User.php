<?php
namespace common\models;

use common\classes\Dop;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property int $id
 * @property string $auth_key
 * @property string $password write-only password
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_confirm_token
 * @property string $created_at
 * @property string $updated_at
 * @property int $is_tester
 * @property int $is_moderator
 * @property int $is_verified
 * @property int $is_active
 * @property string $name
 * @property string $second_name
 * @property string $surname
 * @property string $login
 * @property int $phone
 * @property string $registered_at
 * @property string $authorized_at
 * @property string $action_at
 * @property string $random_winner_at
 * @property string $imported_at
 * @property array $dop_column
 * @property string $signature
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const ACTIVE_ACTIVE = 1;
    public const EMAIL_VERIFIED = 1;

    public static $defaultDbUserColumn = ['login', 'email', 'password', 'name', 'second_name', 'surname', 'phone'];
    public static $excludeVariables = ['password'];
    public $file;
    public $dop = [];
    public $password;
    public $configDopColumn;
    public $signature;

    public function init()
    {
        $this->configDopColumn = Yii::$app->configUserDopColumn;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],

            [['login', 'email'], 'filter', 'filter' => 'trim'],

            [['is_tester', 'is_moderator', 'is_verified', 'is_active', 'phone'], 'integer'],

            [['created_at', 'updated_at', 'registered_at', 'authorized_at', 'action_at', 'random_winner_at', 'imported_at'], 'safe'],

            [['password_hash', 'password_reset_token', 'email', 'email_confirm_token', 'name', 'second_name', 'surname', 'login'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],

            [['email'], 'email'],
            [['email'], 'unique'],
            [['email'], 'unique', 'targetAttribute' => 'login'],
            [['email'], 'either', 'skipOnEmpty' => false, 'params' => ['other' => 'login']],

            [['login'], 'unique'],
            [['login'], 'unique', 'targetAttribute' => 'email'],
            [['login'], 'either', 'skipOnEmpty' => false, 'params' => ['other' => 'email']],

            [['password_reset_token'], 'unique'],

            [['password'], 'required', 'on' => 'create'],
            [['password'], 'string', 'min' => 6],

            [['email_confirm_token'], 'unique'],

            [['is_tester', 'is_moderator', 'is_verified'], 'default', 'value' => 0],
            [['is_active'], 'default', 'value' => 1],

            [['dop_column'], 'safe'],

            ['file', 'file', 'extensions' => ['csv']],
        ];
    }

    /**
     * @param $attribute_name
     * @param $params
     */
    public function either($attribute_name, $params)
    {
        $field1 = $this->getAttributeLabel($attribute_name);
        if (empty($this->$attribute_name) && empty($this->{$params['other']})) {
            $this->addError($attribute_name, "Необходимо заполнить «{$field1}».");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password' => 'Пароль',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Token сброса пароля',
            'login' => 'Логин',
            'email' => 'E-mail',
            'email_confirm_token' => 'Token подтверждения почты',
            'created_at' => 'Добавлен',
            'updated_at' => 'Отредактирован',
            'is_tester' => 'Тестировщик',
            'is_moderator' => 'Модератор',
            'is_verified' => 'Подтверждена почта',
            'is_active' => 'Активен',
            'name' => 'Имя',
            'second_name' => 'Отчество',
            'surname' => 'Фамилия',
            'phone' => 'Телефон',
            'registered_at' => 'Зарегестрирован',
            'authorized_at' => 'Авторизирован',
            'action_at' => 'Онлайн',
            'random_winner_at' => 'Победитель',
            'imported_at' => 'Спарсен',
            'dop_column' => 'Доп поля',
            'signature' => 'Подпись'
        ];
    }


    public function afterFind()
    {
        parent::afterFind();

        $this->dop = !empty($this->dop_column) ? (array)json_decode($this->dop_column) : [];
        $this->getSignature();
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }

            $this->login = $this->login === '' ? null : $this->login;
            $this->email = $this->email === '' ? null : $this->email;

            $this->dop_column = json_encode($this->dop, JSON_UNESCAPED_UNICODE);

            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (!empty($this->password)) {
                $this->setPassword($this->password);
            }

            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes($names = null, $except = [])
    {
        $attributes = parent::getAttributes($names, $except);

        $domain = trim(trim($_ENV['APP_DOMAIN_FRONT']), '/');

        $attributes['linkConfirmationEmail'] = !empty($this->email_confirm_token) ? $domain . '/c/' . $this->email_confirm_token : null;
        $attributes['linkResetPassword'] = !empty($this->password_reset_token) ? $domain . '/r/' . $this->password_reset_token : null;

        if (!empty($this->dop)) {
            foreach ($this->dop as $key => $value) {
                $attributes["{$key}"] = $value;
            }
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_active' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by login
     *
     * @param string $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::find()->where([
            'and',
            [
                'or',
                ['login' => $login],
                ['email' => $login],
            ],
            ['is_active' => User::ACTIVE_ACTIVE]
        ])->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = (int)Yii::$app->config->tokenLifetime * 60;
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = 'r_' . Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Generates new email confirm token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = 'c_' . Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes email verified
     */
    public function emailVerified()
    {
        $this->email_confirm_token = null;
        $this->is_verified = 1;
    }

    public static function import($handle)
    {
        $success = true;
        $count = 0;
        $lines = 0;
        $i = 1;
        $errors = [];
        $configs = [];

        if ($userDopColumns = UserDopColumn::find()->where('pars_column is not null')->asArray()->all()) {
            foreach ($userDopColumns as $column) {
                $key = $column['key'];
                $configs[$key] = $column;
                if (in_array($key, User::$defaultDbUserColumn)) {
                    $configs[$key]['attribute'] = true;
                } else {
                    $configs[$key]['attribute'] = false; // dop
                }
            }
        }

        if ($configs != []) {
            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

                if ($i === 1) {
                    $i++;
                    continue;
                }

                $model = new self(['scenario' => 'create']);
                $model->is_active = 1;
                $model->is_verified = 1;
                $model->imported_at = Dop::dateTime();

                foreach ($configs as $config) {
                    $key = $config['key'];
                    $pars_column = (int)$config['pars_column'] - 1;
                    if ($config['attribute']) {
                        $model->$key = $data[$pars_column];
                    } else {
                        $model->dop[$key] = $data[$pars_column];
                    }
                }

                if ($model->save()) {
                    $count++;
                } else {
                    $errors[$i] = $model->getErrors();
                    $success = false;
                }

                $i++;
            }
        }
        return ['success' => $success, 'count' => $count, 'lines' => $lines, 'errors' => $errors];
    }

    public function getSignature()
    {
        if (!empty($this->configDopColumn->configSignature)) {
            $signature = $this->configDopColumn->stringSignature;
            foreach ($this->configDopColumn->configSignature as $item) {
                $value = !empty($this->attributes[$item['name']])
                    ? $this->attributes[$item['name']]
                    : (!empty($this->dopColumns[$item['name']])
                        ? $this->dopColumns[$item['name']]
                        : null);

                $signature = str_replace("{{$item['key']}}", ($item['count'] !== null ? mb_substr($value, 0, $item['count']) : $value), $signature);
            }
        }

        $this->signature = !empty($signature) ? trim($signature) : null;
    }
}
