<?php

namespace frontend\modules\chat\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\chat\models\Chat;

/**
 * ChatSearch represents the model behind the search form of `frontend\modules\chat\models\Chat`.
 */
class ChatSearch extends Chat {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['chat_message_id', 'message_from', 'message_to', 'datetime', 'is_read', 'deleted'], 'integer'],
            [['message'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Chat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want message_to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'chat_message_id' => $this->chat_message_id,
            'message_from' => $this->message_from,
            'message_to' => $this->message_to,
            'datetime' => $this->datetime,
            'is_read' => $this->is_read,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }

}
