<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reward\models\MstElement;

/**
 * MstElementSearch represents the model behind the search form of `reward\models\MstElement`.
 * @property mixed status
 * @property mixed type
 */
class MstElementSearch extends MstElement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['element_name', 'isYear', 'status', 'type', 'created_at', 'updated_at'], 'safe'],
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
        $query = MstElement::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'element_name', $this->element_name])
            ->andFilterWhere(['like', 'isYear', $this->isYear])
            ->andFilterWhere(['type', 'isYear', $this->type])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
