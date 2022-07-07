<?php

namespace frontend\modules\api_v1\controllers;

use common\filters\auth\HttpBearerAuth;
use common\models\Chat;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
use common\models\SendEmail;
use common\models\User;
use common\models\UserDopColumn;
use common\classes\Dop;
use frontend\modules\api_v1\components\ApiController;
use frontend\modules\api_v1\models\LoginForm;
use frontend\modules\api_v1\models\Token;
use Yii;
use yii\filters\Cors;
use yii\filters\VerbFilter;

/**
 * Default controller for the `api_v1` module
 */
class UserController extends ApiController
{
    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Headers' => ['*']
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'form-signup' => ['GET'],
                'signup' => ['POST'],
                'send-confirmation-email' => ['POST'],
                'confirmation-email' => ['POST'],
                'form-login' => ['GET'],
                'login' => ['POST'],
                'logout' => ['POST'],
                'request-password-reset' => ['POST'],
                'check-reset-password' => ['GET'],
                'reset-password' => ['POST'],
                'info' => ['GET'],
                'chat' => ['GET'],
            ],
        ];

        unset($behaviors['authenticator']);
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['form-signup', 'signup', 'send-confirmation-email', 'confirmation-email', 'form-login', 'login',
                'request-password-reset', 'check-reset-password', 'reset-password'],
        ];

        return $behaviors;
    }

    /**
     * @return array|false[]
     */
    public function actionFormSignup()
    {
        if ($dopColumn = UserDopColumn::find()->where(['registration' => 1])->orderBy(['registration_sort' => SORT_ASC])->asArray()->all()) {
            $fields = [];

            foreach ($dopColumn as $item) {
                $list = null;
                if ($item['type'] === 'select') {
                    $list = array_map('trim', explode(',', $item['select_list']));
                }

                $fields[] = [
                    'name' => $item['key'],
                    'label' => $item['title'],
                    'type' => $item['type'],
                    'required' => (boolean)$item['required'],
                    'sort' => (int)$item['registration_sort'],
                    'list' => $list
                ];
            }

            return ['status' => 200, 'fields' => $fields];
        }

        return ['status' => 422];
    }

    /**
     * @return array|bool[]
     */
    public function actionSignup()
    {
        $data = Yii::$app->request->post();
        $errors = [];

        $modelUser = new User(['scenario' => 'create']);
        $modelUser->attributes = $data;

        if (!$validate = $modelUser->validate()) {
            $errors = $modelUser->errors;
        }

        if (!empty($modelUser->configDopColumn)) {
            $configColumn = $modelUser->configDopColumn->dopColumn;

            foreach ($configColumn as $column) {
                $key = $column['key'];

                if (
                    (!array_key_exists($key, $data) && $column['registration'] == 1) ||
                    (array_key_exists($key, $data) && $column['registration'] == 1 && $column['required'] == 1 && empty($data[$key]))
                ) {
                    $errors[$key][] = "Необходимо заполнить «{$column['title']}»";
                }

                if (!array_key_exists($key, User::$defaultDbUserColumn)) {
                    $modelUser->dop[$key] = !empty($data[$key]) ? $data[$key] : null;
                }
            }
        }

        $modelUser->registered_at = Dop::dateTime();

        if ($errors !== [] || !$modelUser->save()) {
            return ['status' => 422, 'errors' => $errors];
        }

        // Todo: Уточнить что делать если в настройках включено подтверждение почты но почты нет при регистрации или она не обязательна

        if (!!Yii::$app->config->confirmationEmail) {
            $modelUser->generateEmailConfirmToken();
            $modelUser->is_verified = 0;
            $modelUser->save(false);

            SendEmail::sendTemplate($modelUser->id, 'confirmationEmail');
        } else {
            $modelUser->is_verified = 1;
            $modelUser->save(false);

            if ($token = Token::auth($modelUser)) {
                $auth = [
                    'token' => $token->token,
                    'expired' => $token->expired
                ];
            }
        }

        return ['status' => 200, 'auth' => $auth ?? null];
    }

    /**
     * @return array|int[]
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionSendConfirmationEmail()
    {
        if (!!Yii::$app->config->confirmationEmail) {
            if ($user = User::find()->where(['email' => Yii::$app->request->post('email')])->one()) {
                $user->generateEmailConfirmToken();
                if ($user->save(false) && SendEmail::sendTemplate($user->id, 'confirmationEmail')) {
                    return ['status' => 200];
                }
            }

            return ['status' => 422, 'errors' => ['email' => ['Пользователь не найден']]];
        }

        throw new \yii\web\NotFoundHttpException('Страница не найдена.');
    }

    /**
     * @return array
     */
    public function actionConfirmationEmail()
    {
        if ($user = User::find()->where(['email_confirm_token' => Yii::$app->request->post('code')])->one()) {
            $user->emailVerified();
            if ($user->save(false)) {
                $auth = null;
                if (!!$user->is_active && $token = Token::auth($user)) {
                    $auth = [
                        'token' => $token->token,
                        'expired' => $token->expired
                    ];
                }

                return [
                    'status' => 200,
                    'auth' => $auth
                ];
            }
        }

        return ['status' => 422, 'errors' => ['code' => ['Неверный код']]];
    }

    /**
     * @return array
     */
    public function actionFormLogin()
    {
        $fields = [
            [
                'name' => 'login',
                'label' => Yii::$app->config->authorizationField,
                'type' => 'string',
                'required' => 'true',
            ]
        ];

        if (Yii::$app->config->authorizationPassword) {
            $fields[] = [
                'name' => 'password',
                'label' => 'Пароль',
                'type' => 'password',
                'required' => 'true',
            ];
        }

        return ['status' => 200, 'fields' => $fields];
    }

    /**
     * @return array
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            return [
                'status' => 200,
                'auth' => [
                    'token' => $token->token,
                    'expired' => $token->expired
                ]
            ];
        }

        return ['status' => 422, 'errors' => $model->errors];
    }

    /**
     * @return bool[]|false[]
     * @throws \yii\db\StaleObjectException
     */
    public function actionLogout()
    {
        $token = !empty(Yii::$app->user->identity) ? Yii::$app->user->identity : null;

        if ($model = Token::findOne(['id' => $token->id])) {
            if ($model->delete()) {
                return ['status' => 200];
            }
        } else {
            return ['status' => 422];
        }
    }

    /**
     * @return array|bool[]
     */
    public function actionRequestPasswordReset()
    {
        if (!Yii::$app->config->resetPassword) {
            throw new \yii\web\NotFoundHttpException('Страница не найдена.');
        }

        $model = new PasswordResetRequestForm(['email' => Yii::$app->request->post('email')]);
        if ($model->validate()) {
            if ($model->sendEmail()) {
                return ['status' => 200];
            }
        }

        return ['status' => 422, 'errors' => $model->errors];
    }

    /**
     * @param $token
     * @return bool[]|false[]
     */
    public function actionCheckResetPassword($token = null)
    {
        if (!Yii::$app->config->resetPassword) {
            throw new \yii\web\NotFoundHttpException('Страница не найдена.');
        }

        if (User::findByPasswordResetToken($token)) {
            return ['status' => 200];
        }

        return ['status' => 422, 'errors' => ['token' => ['Неверный токен']]];
    }

    /**
     * @return array|bool[]|false[]
     */
    public function actionResetPassword()
    {
        if (!Yii::$app->config->resetPassword) {
            throw new \yii\web\NotFoundHttpException('Страница не найдена.');
        }

        $token = Yii::$app->request->post('token');
        $password = Yii::$app->request->post('password');

        if ($user = User::findByPasswordResetToken($token)) {
            $model = new ResetPasswordForm($token);
            $model->password = $password;

            if ($model->validate() && $model->resetPassword()) {
                return ['status' => 200];
            }

            return ['status' => 422, 'errors' => $model->errors];
        }

        return ['status' => 422, 'errors' => ['token' => ['Неверный токен']]];
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionInfo()
    {
        $user = Yii::$app->user->identity->user;

        $data = [
            'id' => $user['id'],
            'signature' => $user['signature'],
            'login' => $user['login'],
            'email' => $user['email'],
            'name' => $user['name'],
            'second_name' => $user['second_name'],
            'surname' => $user['surname'],
            'phone' => $user['phone'],
            'registered_at' => !empty($user['registered_at']) ? Yii::$app->formatter->asDatetime($user['registered_at']) : null,
            'authorized_at' => !empty($user['authorized_at']) ? Yii::$app->formatter->asDatetime($user['authorized_at']) : null,
            'action_at' => !empty($user['action_at']) ? Yii::$app->formatter->asDatetime($user['action_at']) : null,
            'random_winner_at' => !empty($user['random_winner_at']) ? Yii::$app->formatter->asDatetime($user['random_winner_at']) : null,
            'is_tester' => !!$user['is_tester'],
            'is_moderator' => !!$user['is_moderator'],
            'is_verified' => !!$user['is_verified'],
        ];

        return ['status' => 200, 'data' => $data];
    }

    public function actionChat($id = null, $limit = 10)
    {
        $models = Chat::find()->where(['status' => Chat::STATUS_APPROVED]);

        if ($id !== null) {
            $models->andWhere(['<', 'id', $id]);
        }

        if ($modelsAll = $models->orderBy(['created_at' => SORT_DESC])->limit($limit)->all()) {
            $messages = [];
            foreach ($modelsAll as $item) {
                $messages[] = $item->formationData();
            }
        }

        return ['status' => 200, 'messages' => $messages ?? null];
    }
}
