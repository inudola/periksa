<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reward\models\BatchDetail;

/**
 * BatchDetailSearch represents the model behind the search form of `reward\models\BatchDetail`.
 */
class BatchDetailSearch extends BatchDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'simulation_id', 'bulan', 'tahun', 'batch_id'], 'integer'],
            [['element', 'created_at', 'updated_at'], 'safe'],
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
        $query = BatchDetail::find();

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
            'simulation_id' => $this->simulation_id,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'amount' => $this->amount,
            'batch_id' => $this->batch_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'element', $this->element]);

        return $dataProvider;
    }

    public function search1($params)
    {
        $query = BatchDetail::find();

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
            'simulation_id' => $this->simulation_id,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'amount' => $this->amount,
            'batch_id' => $this->batch_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'element', $this->element]);

        return $dataProvider;
    }


}
