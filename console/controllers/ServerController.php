<?php

namespace console\models;

namespace app\commands;

namespace console\controllers;

use yii\console\Controller;
use console\models\ChatServer;

class ServerController extends Controller {

    public function actionStart() {
        $server = new ChatServer();
        $server->port = 1000; //This port must be busy by WebServer and we handle an error

        $server->on(ChatServer::EVENT_WEBSOCKET_OPEN_ERROR, function($e) use($server) {
            echo "Error opening port " . $server->port . "\n";
            $server->port += 1; //Try next port to open
            $server->start();
        });

        $server->on(ChatServer::EVENT_WEBSOCKET_OPEN, function($e) use($server) {
            echo "Server started at port " . $server->port;
        });

        $server->start();
    }

}
