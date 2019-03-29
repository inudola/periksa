<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reward\models\Insentif;

/**
 * InsentifSearch represents the model behind the search form of `reward\models\Insentif`.
 */
class InsentifSearch extends Insentif
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'smt', 'tahun'], 'integer'],
            [['nik', 'bi', 'band', 'organisasi_nku', 'tipe_organisasi', 'created_at', 'updated_at'], 'safe'],
            [['nkk', 'nku', 'nki'], 'number'],
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
        $query = Insentif::find();

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
            'nkk' => $this->nkk,
            'nku' => $this->nku,
            'nki' => $this->nki,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nik', $this->nik])
            ->andFilterWhere(['like', 'bi', $this->bi])
            ->andFilterWhere(['like', 'band', $this->band])
            ->andFilterWhere(['like', 'organisasi_nku', $this->organisasi_nku])
            ->andFilterWhere(['like', 'tipe_organisasi', $this->tipe_organisasi]);

        return $dataProvider;
    }
}
