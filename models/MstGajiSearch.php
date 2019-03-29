<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reward\models\MstGaji;

/**
 * MstGajiSearch represents the model behind the search form of `reward\models\MstGaji`.
 */
class MstGajiSearch extends MstGaji
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gaji_dasar', 'tunjangan_biaya_hidup', 'tunjangan_jabatan_struktural', 'tunjangan_jabatan_functional', 'tunjangan_rekomposisi'], 'integer'],
            [['bi', 'created_at', 'updated_at'], 'safe'],
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
        $query = MstGaji::find();

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
            'gaji_dasar' => $this->gaji_dasar,
            'tunjangan_biaya_hidup' => $this->tunjangan_biaya_hidup,
            'tunjangan_jabatan_struktural' => $this->tunjangan_jabatan_struktural,
            'tunjangan_jabatan_functional' => $this->tunjangan_jabatan_functional,
            'tunjangan_rekomposisi' => $this->tunjangan_rekomposisi,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'bi', $this->bi]);

        return $dataProvider;
    }
}
