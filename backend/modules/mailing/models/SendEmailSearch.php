<?php

namespace backend\modules\mailing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\SendEmail;

/**
 * SendEmailSearch represents the model behind the search form of `common\models\SendEmail`.
 */
class SendEmailSearch extends SendEmail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'template_id'], 'integer'],
            [['created_at', 'updated_at', 'email', 'theme', 'html', 'sended_at', 'error'], 'safe'],
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
        $query = SendEmail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'template_id' => $this->template_id,
            'sended_at' => $this->sended_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'theme', $this->theme])
            ->andFilterWhere(['like', 'html', $this->html])
            ->andFilterWhere(['like', 'error', $this->error]);

        return $dataProvider;
    }
}
