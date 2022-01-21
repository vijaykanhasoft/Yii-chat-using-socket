<?php

namespace frontend\modules\chat\models;

use Yii;

/**
 * This is the model class for table "chat_messages".
 *
 * @property int $chat_message_id
 * @property string $message
 * @property int $message_from
 * @property int $message_to
 * @property int $datetime
 * @property int $is_read
 * @property int $deleted
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'message_from', 'message_to', 'datetime', 'is_read', 'deleted'], 'required'],
            [['message'], 'string'],
            [['message_from', 'message_to', 'datetime', 'is_read', 'deleted'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'chat_message_id' => 'Chat Message ID',
            'message' => 'Message',
            'message_from' => 'message_from',
            'message_to' => 'message_to',
            'datetime' => 'Datetime',
            'is_read' => 'Is Read',
            'deleted' => 'Deleted',
        ];
    }
}
