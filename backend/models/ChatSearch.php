<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Chat;

/**
 * ChatSearch represents the model behind the search form of `common\models\Chat`.
 */
class ChatSearch extends Chat
{
    public $isModerator = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'moderator_id', 'message_id', 'speaker_id'], 'integer'],
            [['created_at', 'updated_at', 'message', 'isModerator'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Chat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        if ($this->isModerator) {
            $query->with(['answerTo', 'response']);

            $query->andWhere('moderator_id is null or moderator_id = ' . Yii::$app->user->identity->id);
            $query->andWhere('message_id is null');
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'moderator_id' => $this->moderator_id,
            'message_id' => $this->message_id,
            'speaker_id' => $this->speaker_id,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
