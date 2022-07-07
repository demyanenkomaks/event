<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserDopColumn;

/**
 * UserDopColumnSearch represents the model behind the search form of `common\models\UserDopColumn`.
 */
class UserDopColumnSearch extends UserDopColumn
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'deleteConfig', 'required', 'registration', 'registration_sort', 'pars_column'], 'integer'],
            [['key', 'title', 'type', 'select_list'], 'safe'],
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
        $query = UserDopColumn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'deleteConfig' => $this->deleteConfig,
            'required' => $this->required,
            'registration' => $this->registration,
            'registration_sort' => $this->registration_sort,
            'pars_column' => $this->pars_column,
        ]);

        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'select_list', $this->select_list]);

        return $dataProvider;
    }
}
