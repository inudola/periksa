<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reward\models\Setting;

/**
 * SettingSearch represents the model behind the search form of `reward\models\Setting`.
 */
class SettingSearch extends Setting
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['setup_name', 'status', 'group_nature', 'description', 'created_at', 'updated_at'], 'safe'],
            [['value_max'], 'number'],

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
        $query = Setting::find();

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
            'value_max' => $this->value_max,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'setup_name', $this->setup_name])
            ->andFilterWhere(['like', 'group_nature', $this->group_nature])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
