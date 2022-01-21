<?php

namespace app\daemons;

namespace console\models;

use yii;
use consik\yii2websocket\events\WSClientEvent;
use consik\yii2websocket\WebSocketServer;
use Ratchet\ConnectionInterface;
use backend\modules\user\models\User;
use frontend\modules\chat\models\Chat;
use yii\helpers\Json;

class ChatServer extends WebSocketServer {

    public $Users;

    public function init() {
        parent::init();

        $this->on(self::EVENT_CLIENT_CONNECTED, function(WSClientEvent $e) {
            $e->client->name = null;
        });
    }

    protected function getCommand(ConnectionInterface $from, $msg) {
        $request = json_decode($msg, true);
        return !empty($request['action']) ? $request['action'] : parent::getCommand($from, $msg);
    }

    public function commandChat(ConnectionInterface $client, $msg) {
        $request = json_decode($msg, true);
        $result = ['message' => ''];

        if (!$client->name) {
            $result['message'] = 'Set your name';
        } elseif (!empty($request['message']) && !empty($request['to']) && ($message = trim($request['message'])) && ($to = trim($request['to'])) && ($from = trim($request['from']))) {
            $messageData = array(
                'Chat' => array(
                    'message' => $request['message'],
                    'message_from' => $request['from'],
                    'message_to' => $request['to'],
                    'datetime' => $request['date_time']
                )
            );
            $ChatModel = new Chat();
            $ChatModel->load($messageData);
            $result = $ChatModel->save(false);
            foreach ($this->clients as $chatClient) {
                if ($to == $chatClient->user_id) {
                    $chatClient->send(json_encode([
                        'type' => 'chat',
                        'from' => $client->name,
                        'from_id' => $client->user_id,
                        'to' => $to,
                        'message' => $message
                    ]));
                }
                if ($from == $chatClient->user_id) {
                    $chatClient->send(json_encode([
                        'type' => 'chat',
                        'from' => $client->name,
                        'from_id' => $client->user_id,
                        'to' => $to,
                        'message' => $message
                    ]));
                }
            }
        } else {
            $result['message'] = 'Enter message';
        }

        $client->send(json_encode($result));
    }

    public function commandSetName(ConnectionInterface $client, $msg) {
        $request = json_decode($msg, true);
        if (!empty($request['id']) && $user_id = trim($request['id'])) {
            $userDetail = User::_getUserById($user_id);
            if ($userDetail) {
                $client->name = ucfirst($userDetail->username);
                $client->user_id = $userDetail->id;
                $result = ['message' => "Welcome " . ucfirst($userDetail->username) . ", You are successfully logged in."];
            } else {
                $result['message'] = 'Invalid Login';
            }
        } else {
            $result['message'] = 'Invalid username';
        }

        $client->send(json_encode($result));
    }

    public function commandcalendarNotification(ConnectionInterface $client, $request) {
        $requestParams = json_decode($request, true);
        $Query = "SELECT 
                        calendar_events.*, calendar_events.event_start_time
                    FROM
                        `calendar_events`
                    WHERE
                        DATE_ADD(FROM_UNIXTIME(calendar_events.event_start_time),
                            INTERVAL 10 MINUTE) >= {$requestParams['date_time']}";

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($Query);
        $chatList = $command->queryAll();
        $response = array('type' => 'notification', 'data' => $chatList);
        $client->send(json_encode($response));
    }

}
