<?php

namespace reward\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RewardSearch represents the model behind the search form of `app\models\Reward`.
 */
class RewardSearch extends Reward
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mst_reward_id'], 'integer'],
            [['emp_category', 'band_individu', 'band_position', 'gender', 'structural', 'functional', 'marital_status', 'created_at', 'updated_at'], 'safe'],
            [['amount'], 'number'],
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
        $query = Reward::find()->orderBy(['id' => SORT_DESC]);

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
            'amount' => $this->amount,
            'mst_reward_id' => $this->mst_reward_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'emp_category', $this->emp_category])
            ->andFilterWhere(['like', 'band_individu', $this->band_individu])
            ->andFilterWhere(['like', 'band_position', $this->band_position])
            ->andFilterWhere(['like', 'structural', $this->structural])
            ->andFilterWhere(['like', 'functional', $this->functional])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'marital_status', $this->marital_status]);

        return $dataProvider;
    }

    public function search1($params)
    {
        $query = Reward::find()->orderBy(['band_individu' => SORT_ASC, 'band_position' => SORT_ASC ]);

        // add conditions that should always apply here

        $dataProvider1 = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider1;
        }


        return $dataProvider1;
    }
}
