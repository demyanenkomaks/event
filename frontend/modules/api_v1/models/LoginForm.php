<?php

namespace frontend\modules\api_v1\models;

use common\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $login;
    public $password;
    public $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['login'], 'required'],
        ];

        if (!!Yii::$app->config->authorizationPassword) {
            $rules = array_merge($rules, [
                [['password'], 'required'],
                [['password'], 'validatePassword'],
            ]);
        } else {
            $rules = array_merge($rules, [
                [['login'], 'validatePassword'],
            ]);
        }

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'login' => Yii::$app->config->authorizationField,
            'password' => 'Пароль',
        ];
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $error = true;

            $user = $this->getUser();
            if ($error && (!$user || $user->is_active != User::ACTIVE_ACTIVE)) {
                $this->addError($attribute, 'Проверьте корректность вводимых данных.');
                $error = false;
            }

            if ($error && !!Yii::$app->config->authorizationPassword && (!$user || !$user->validatePassword($this->password))) {
                $this->addError($attribute, 'Проверьте корректность вводимых данных.');
            }

            if (!!Yii::$app->config->confirmationEmail && (!$user || $user->is_verified != User::EMAIL_VERIFIED)) {
                $this->addError($attribute, 'Обратитесь в поддержку.');
            }
        }
    }

    /**
     * @return false|Token|null
     */
    public function auth()
    {
        if ($this->validate()) {
            $user = $this->getUser();

            return Token::auth($user);
        }
        return false;
    }

    /**
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByLogin($this->login);
        }

        return $this->_user;
    }

}
