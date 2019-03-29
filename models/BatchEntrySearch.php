<?php

namespace reward\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BatchEntrySearch represents the model behind the search form of `projection\models\BatchEntry`.
 */
class BatchEntrySearch extends BatchEntry
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'jumlah_orang', 'amount'], 'integer'],
            [['bi', 'bp', 'created_at', 'updated_at'], 'safe'],
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
        $query = BatchEntry::find();

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
            'type_id' => $this->type_id,
            'jumlah_orang' => $this->jumlah_orang,
            'amount'    => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'bi', $this->bi])
            ->andFilterWhere(['like', 'bp', $this->bp]);

        return $dataProvider;
    }
}
