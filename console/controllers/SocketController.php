<?php

namespace console\controllers;


use console\components\SocketServer;
use console\models\UserSocket;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Yii;
use yii\web\Request;

class SocketController extends \yii\console\Controller
{
    public function actionStart($port = 8085)
    {
        UserSocket::deleteAll(); // Очищение все сокет соединения

        Yii::$app->set('request', new Request());
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SocketServer()
                )
            ),
            $port
        );
        $server->run();
    }
}
