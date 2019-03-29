<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reward\models\Simulation;

/**
 * SimulationSearch represents the model behind the search form of `projection\models\Simulation`.
 */
class SimulationSearch extends Simulation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['start_date', 'end_date', 'effective_date', 'created_at', 'updated_at'], 'safe'],
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
        $query = Simulation::find()->where(['id'=>SORT_DESC])-> one();


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
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'start_date' => $this->start_date,
//            'end_date' => $this->end_date,
//            'effective_date' => $this->effective_date,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//        ]);

        return $dataProvider;
    }
}
