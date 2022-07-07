<?php

namespace backend\models;

use common\classes\Dop;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $timeFrom = '00:00:00';
    public $timeBefore = '23:59:59';

    public $updatedAtFrom;
    public $updatedAtBefore;
    public $registeredAtFrom;
    public $registeredAtBefore;
    public $authorizedAtFrom;
    public $authorizedAtBefore;
    public $actionAtFrom;
    public $actionAtBefore;
    public $randomWinnerAtFrom;
    public $randomWinnerAtBefore;
    public $importedAtFrom;
    public $importedAtBefore;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_tester', 'is_moderator', 'is_verified', 'is_active', 'phone'], 'integer'],
            [['auth_key', 'password_hash', 'password_reset_token', 'email', 'email_confirm_token', 'created_at', 'updated_at',
                'name', 'second_name', 'surname', 'login', 'registered_at', 'authorized_at', 'action_at', 'random_winner_at', 'imported_at',
                'updatedAtFrom', 'updatedAtBefore', 'registeredAtFrom', 'registeredAtBefore', 'authorizedAtFrom', 'authorizedAtBefore',
                'actionAtFrom', 'actionAtBefore', 'randomWinnerAtFrom', 'randomWinnerAtBefore', 'importedAtFrom', 'importedAtBefore'], 'safe'],
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
        $query = User::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_tester' => $this->is_tester,
            'is_moderator' => $this->is_moderator,
            'is_verified' => $this->is_verified,
            'is_active' => $this->is_active,
            'phone' => $this->phone,
            'registered_at' => $this->registered_at,
            'authorized_at' => $this->authorized_at,
            'action_at' => $this->action_at,
            'random_winner_at' => $this->random_winner_at,
            'imported_at' => $this->imported_at,
        ]);

        $query->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'email_confirm_token', $this->email_confirm_token])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'second_name', $this->second_name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'login', $this->login]);



        if ($this->updatedAtFrom) {
            $query->andFilterWhere([
                '>=',
                'updated_at',
                Yii::$app->formatter->asDateTime($this->updatedAtFrom . ' ' . $this->timeFrom, Dop::$formatDateTimeDb)
            ]);
        }
        if ($this->updatedAtBefore) {
            $query->andFilterWhere([
                '<=',
                'updated_at',
                Yii::$app->formatter->asDateTime($this->updatedAtBefore . ' ' . $this->timeBefore, Dop::$formatDateTimeDb)
            ]);
        }

        if ($this->registeredAtFrom) {
            $query->andFilterWhere([
                '>=',
                'registered_at',
                Yii::$app->formatter->asDateTime($this->registeredAtFrom . ' ' . $this->timeFrom, Dop::$formatDateTimeDb)
            ]);
        }
        if ($this->registeredAtBefore) {
            $query->andFilterWhere([
                '<=',
                'registered_at',
                Yii::$app->formatter->asDateTime($this->registeredAtBefore . ' ' . $this->timeBefore, Dop::$formatDateTimeDb)
            ]);
        }

        if ($this->authorizedAtFrom) {
            $query->andFilterWhere([
                '>=',
                'authorized_at',
                Yii::$app->formatter->asDateTime($this->authorizedAtFrom . ' ' . $this->timeFrom, Dop::$formatDateTimeDb)
            ]);
        }
        if ($this->authorizedAtBefore) {
            $query->andFilterWhere([
                '<=',
                'authorized_at',
                Yii::$app->formatter->asDateTime($this->authorizedAtBefore . ' ' . $this->timeBefore, Dop::$formatDateTimeDb)
            ]);
        }

        if ($this->actionAtFrom) {
            $query->andFilterWhere([
                '>=',
                'action_at',
                Yii::$app->formatter->asDateTime($this->actionAtFrom . ' ' . $this->timeFrom, Dop::$formatDateTimeDb)
            ]);
        }
        if ($this->actionAtBefore) {
            $query->andFilterWhere([
                '<=',
                'action_at',
                Yii::$app->formatter->asDateTime($this->actionAtBefore . ' ' . $this->timeBefore, Dop::$formatDateTimeDb)
            ]);
        }

        if ($this->randomWinnerAtFrom) {
            $query->andFilterWhere([
                '>=',
                'random_winner_at',
                Yii::$app->formatter->asDateTime($this->randomWinnerAtFrom . ' ' . $this->timeFrom, Dop::$formatDateTimeDb)
            ]);
        }
        if ($this->randomWinnerAtBefore) {
            $query->andFilterWhere([
                '<=',
                'random_winner_at',
                Yii::$app->formatter->asDateTime($this->randomWinnerAtBefore . ' ' . $this->timeBefore, Dop::$formatDateTimeDb)
            ]);
        }

        if ($this->importedAtFrom) {
            $query->andFilterWhere([
                '>=',
                'imported_at',
                Yii::$app->formatter->asDateTime($this->importedAtFrom . ' ' . $this->timeFrom, Dop::$formatDateTimeDb)
            ]);
        }
        if ($this->importedAtBefore) {
            $query->andFilterWhere([
                '<=',
                'imported_at',
                Yii::$app->formatter->asDateTime($this->importedAtBefore . ' ' . $this->timeBefore, Dop::$formatDateTimeDb)
            ]);
        }

        return $dataProvider;
    }
}
