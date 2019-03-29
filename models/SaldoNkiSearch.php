<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reward\models\SaldoNki;

/**
 * SaldoNkiSearch represents the model behind the search form of `projection\models\SaldoNki`.
 */
class SaldoNkiSearch extends SaldoNki
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'smt', 'tahun', 'total'], 'integer'],
            [['nik', 'bi', 'created_at', 'updated_at'], 'safe'],
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
        $query = SaldoNki::find();

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
            'smt' => $this->smt,
            'tahun' => $this->tahun,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nik', $this->nik]);
        $query->andFilterWhere(['like', 'bi', $this->bi]);

        return $dataProvider;
    }
}
