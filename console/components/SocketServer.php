<?php

namespace console\components;


use common\classes\Dop;
use common\models\Chat;
use common\models\User;
use console\models\UserSocket;
use frontend\modules\api_v1\models\Token;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Yii;
use yii\base\Component;
use yii\db\Exception;
use yii\helpers\Json;


class SocketServer extends Component implements MessageComponentInterface
{
    protected $clients = [];
    protected $redis;

    public const CONNECTION_BACKEND = 'backend';
    public const CONNECTION_FRONTEND = 'frontend';

    public static $connectionArray = [
        self::CONNECTION_BACKEND,
        self::CONNECTION_FRONTEND,
    ];

    public const APP_EVENT = 'event';
    public const APP_MODERATION_CHAT = 'moderationChat';

    public static $appFrontends = [
        self::APP_EVENT,
    ];

    public static $appBackends = [
        self::APP_MODERATION_CHAT,
    ];

    protected $toAdminActions = [
        'takeInWorkMessage', 'approvalMessage', 'rejectedMessage', 'responseMessage', 'speakerNote',
    ];

    protected $toModerationChatActions = [
        'takeInWorkMessage', 'approvalMessage', 'rejectedMessage', 'responseMessage', 'speakerNote',
    ];

    public function init()
    {
        $this->redis = Yii::$app->redis;
    }

    /**
     * Новое подключение WebSocket
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $user_id = null;
        $connection = null;
        $app = null;

        $httpRequest = $conn->httpRequest;
        $path = $httpRequest->getUri()->getPath();
        parse_str($httpRequest->getUri()->getQuery(), $query);

        if (stripos($path, 'backend') !== false && !empty($query['app']) && in_array($query['app'], self::$appBackends)) {
            $connection = self::CONNECTION_BACKEND;
            $app = $query['app'];
            $cookiesRaw = $httpRequest->getHeader('Cookie');
            $cookiesArr = [];

            if (count($cookiesRaw)) {
                $cookiesArr = \GuzzleHttp\Psr7\parse_header($cookiesRaw)[0];
            }

            if (!empty($_ENV['SESSION_SAVE_PATH'])) {
                session_save_path($_ENV['SESSION_SAVE_PATH']);
            }

            session_id($cookiesArr['advanced-backend']);
            session_start();
            $user_id = $_SESSION['__id'];
            session_abort();

        } elseif (!empty($query['token']) && !empty($query['app']) && in_array($query['app'], self::$appFrontends)) {
            $connection = self::CONNECTION_FRONTEND;
            $app = $query['app'];

            if ($identity = Token::findIdentityByAccessToken($query['token'])) {
                $user_id = $identity['user']['id'];
            }
        }

        if ($user_id && $connection && $app) {
            $this->clients[$conn->resourceId] = $conn;
            error_log('New client connected with id:' . $conn->resourceId . ', path:' . $path . ', user_id:' . $user_id);

            $people = new UserSocket([
                'resourceId' => $conn->resourceId,
                'user_id' => $user_id,
                'connection' => $connection,
                'app' => $app,
            ]);

            if (!$people->save()) {
                error_log('People saving error: ' . print_r($people->getFirstErrors()));
                $conn->close();
            }
        } else {
            error_log('User not defined');
            $conn->close();
        }
    }

    /**
     * Получение нового сообщения на WebSocket и отправка на action
     *
     * @param ConnectionInterface $from
     * @param $msg
     * @throws Exception
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if (is_null($data)) {
            error_log("invalid data\n");
            return $from->close();
        }

        try {
            User::findOne(1);
        } catch (Exception $e) {
            \Yii::$app->db->close();
            \Yii::$app->db->open();
        }

        if ($user = UserSocket::find()->with(['user'])->where(['resourceId' => $from->resourceId])->asArray()->one()) {
            error_log("Message from {$from->resourceId}");

            if (isset($data['action']) && ($command = 'action' . ucfirst(trim(strip_tags($data['action'])))) && $this->hasMethod($command)) {
                if ($this->checkAccess($data['action'], $user['user_id'])) {
                    unset($data['action']);
                    $this->{$command}($from, $user, $data);
                } else {
                    Yii::error([
                        'msg' => "Access denied",
                        'tags' => [
                            'user' => $user,
                            'action' => $command,
                            'data' => $data,
                        ]
                    ], 'socket');
                }
            } else {
                error_log("Undefined action");
            }
        } else {
            error_log("117: Client $from->resourceId does not exist");
            return $from->close();
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        if ($people = UserSocket::find()->where(['resourceId' => $conn->resourceId])->one()) {
            $people->delete();
            unset($this->clients[$conn->resourceId]);
        }
        error_log("Connection {$conn->resourceId} has disconnected\n");
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        error_log("An error has occurred: {$e->getMessage()}\n");
        $conn->close();
    }

    public function clientsAnswer($app = null, $connection = null)
    {
        return UserSocket::find()
            ->filterWhere([
                'app' => $app,
                'connection' => $connection,
            ])
            ->all();
    }

    public function checkAccess($action, $user_id)
    {
        if (
            !in_array($action, $this->toAdminActions) && !in_array($action, $this->toModerationChatActions) ||
            in_array($action, $this->toAdminActions) && Yii::$app->getAuthManager()->checkAccess($user_id, 'admin') ||
            in_array($action, $this->toModerationChatActions) && Yii::$app->getAuthManager()->checkAccess($user_id, 'moderator-chat')
        ) {
            return true;
        }
        return false;
    }

    /**
     *
     * (BACKEND)
     *
     */

    /**
     * Взятие в работу сообщения
     *
     * @param $from
     * @param $user
     * @param array $data
     */
    public function actionTakeInWorkMessage($from, $user, $data = [])
    {
        if (!empty($data) && ($model = Chat::find()->where(['id' => $data['id']])->one())) {
            $model->status = Chat::STATUS_IN_WORK;
            $model->moderator_id = $user['user_id'];
            $model->save(false);

            foreach (self::clientsAnswer(self::APP_MODERATION_CHAT) as $user) {
                if ($client = $this->clients[$user->resourceId]) {
                    $client->send(JSON::encode([
                        'action' => 'moderationMessage',
                    ]));
                }
            }
        } else {
            Yii::error([
                'msg' => "Invalid data",
                'tags' => [
                    'user' => $user,
                    'action' => 'actionTakeInWorkMessage',
                    'data' => $data,
                ]
            ], 'socket');
        }
    }

    /**
     * Одобрение сообщения
     *
     * @param $from
     * @param $user
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     */
    public function actionApprovalMessage($from, $user, $data = [])
    {
        if (!empty($data) && ($model = Chat::find()->with('response')->where(['id' => $data['id']])->one())) {
            $model->status = Chat::STATUS_APPROVED;
            $model->save(false);

            foreach (self::clientsAnswer(self::APP_MODERATION_CHAT) as $user) {
                if ($client = $this->clients[$user->resourceId]) {
                    $client->send(JSON::encode([
                        'action' => 'moderationMessage',
                    ]));
                }
            }

            if (in_array(Yii::$app->config->chatBlock, [1, 2])) {
                foreach (self::clientsAnswer(self::APP_EVENT) as $user) {
                    if ($client = $this->clients[$user->resourceId]) {
                        $client->send(JSON::encode([
                            'action' => 'addMessage',
                            'message' => $model->formationData()
                        ]));
                    }
                }
            }
        } else {
            Yii::error([
                'msg' => "Invalid data",
                'tags' => [
                    'user' => $user,
                    'action' => 'actionApprovalMessage',
                    'data' => $data,
                ]
            ], 'socket');
        }
    }

    /**
     * Отклонение сообщения
     *
     * @param $from
     * @param $user
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRejectedMessage($from, $user, $data = [])
    {
        if (!empty($data) && ($model = Chat::find()->with('response')->where(['id' => $data['id']])->one())) {
            $model->status = Chat::STATUS_REJECTED;
            $model->save(false);

            foreach (self::clientsAnswer(self::APP_MODERATION_CHAT) as $user) {
                if ($client = $this->clients[$user->resourceId]) {
                    $client->send(JSON::encode([
                        'action' => 'moderationMessage',
                    ]));
                }
            }

            if (in_array(Yii::$app->config->chatBlock, [1, 2])) {
                foreach (self::clientsAnswer(self::APP_EVENT) as $user) {
                    if ($client = $this->clients[$user->resourceId]) {
                        $client->send(JSON::encode([
                            'action' => 'deleteMessage',
                            'message' => [
                                'id' => $model->id
                            ]
                        ]));
                    }
                }
            }
        } else {
            Yii::error([
                'msg' => "Invalid data",
                'tags' => [
                    'user' => $user,
                    'action' => 'actionRejectedMessage',
                    'data' => $data,
                ]
            ], 'socket');
        }
    }

    /**
     * Ответить на сообщение
     *
     * @param $from
     * @param $user
     * @param array $data
     */
    public function actionResponseMessage($from, $user, $data = [])
    {
        if (!empty($data) && !empty($data['id']) && !empty($data['message'])) {
            $action = 'updateMessage';
            if (!$model = Chat::find()->with(['answerTo'])->where(['message_id' => $data['id']])->one()) {
                $action = 'addMessage';

                $model = new Chat();
                $model->message_id = $data['id'];
                $model->created_at = Dop::dateTime();
                $model->user_id = $user['user_id'];
                $model->moderator_id = $user['user_id'];
            }

            $model->message = $data['message'];
            $model->status = Chat::STATUS_APPROVED;

            if ($model->save()) {
                $model->answerTo->status = Chat::STATUS_APPROVED;
                $model->answerTo->save(false);

                foreach (self::clientsAnswer(self::APP_MODERATION_CHAT) as $user) {
                    if ($client = $this->clients[$user->resourceId]) {
                        $client->send(JSON::encode([
                            'action' => 'moderationMessage',
                        ]));
                    }
                }

                if (in_array(Yii::$app->config->chatBlock, [1, 2])) {
                    foreach (self::clientsAnswer(self::APP_EVENT) as $user) {
                        if ($client = $this->clients[$user->resourceId]) {
                            $client->send(JSON::encode([
                                'action' => $action,
                                'message' => $model->formationData()
                            ]));
                        }
                    }
                }
            }
        } else {
            Yii::error([
                'msg' => "Invalid data",
                'tags' => [
                    'user' => $user,
                    'action' => 'actionResponseMessage',
                    'data' => $data,
                ]
            ], 'socket');
        }
    }

    /**
     * Отметить спикера
     *
     * @param $from
     * @param $user
     * @param array $data
     */
    public function actionSpeakerNote($from, $user, $data = [])
    {
        if (!empty($data) && !empty($data['idSpeaker']) && ($model = Chat::find()->where(['id' => $data['id']])->one())) {
            $model->speaker_id = $data['idSpeaker'];

            if ($model->save()) {
                foreach (self::clientsAnswer(self::APP_MODERATION_CHAT) as $user) {
                    if ($client = $this->clients[$user->resourceId]) {
                        $client->send(JSON::encode([
                            'action' => 'moderationMessage',
                        ]));
                    }
                }
            }
        } else {
            Yii::error([
                'msg' => "Invalid data",
                'tags' => [
                    'user' => $user,
                    'action' => 'actionSpeakerNote',
                    'data' => $data,
                ]
            ], 'socket');
        }
    }


    /**
     *
     * (FRONTEND)
     *
     */

    /**
     * Получение сообщение с чата
     *
     * @param $from
     * @param $user
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     */
    public function actionMessageChat($from, $user, $data = [])
    {
        if (!empty($data) && !empty($data['message'])) {
            $chatBlock = Yii::$app->config->chatBlock;

            if (in_array($chatBlock, [1, 2])) {
                $model = new Chat();
                $model->user_id = $user['user_id'];
                $model->message = $data['message'];
                $model->status = $chatBlock == 2 ? Chat::STATUS_APPROVED : Chat::STATUS_NEW;
                $model->created_at = Dop::dateTime();

                if ($model->save()) {
                    if ($chatBlock == 1) { // Чат на премодерации
                        foreach (self::clientsAnswer(self::APP_MODERATION_CHAT) as $user) {
                            if ($client = $this->clients[$user->resourceId]) {
                                $client->send(JSON::encode([
                                    'action' => 'moderationMessage',
                                ]));
                            }
                        }
                    } elseif ($chatBlock == 2) { // Чат без модерации
                        foreach (self::clientsAnswer(self::APP_MODERATION_CHAT) as $user) {
                            if ($client = $this->clients[$user->resourceId]) {
                                $client->send(JSON::encode([
                                    'action' => 'moderationMessage',
                                ]));
                            }
                        }

                        foreach (self::clientsAnswer(self::APP_EVENT) as $user) {
                            if ($client = $this->clients[$user->resourceId]) {
                                $client->send(JSON::encode([
                                    'action' => 'addMessage',
                                    'message' => $model->formationData()
                                ]));
                            }
                        }
                    }
                }
            }
        } else {
            Yii::error([
                'msg' => "Invalid data",
                'tags' => [
                    'user' => $user,
                    'action' => 'actionSpeakerNote',
                    'data' => $data,
                ]
            ], 'socket');
        }
    }
}
