<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PayrollResultSearch represents the model behind the search form of `reward\models\PayrollResult`.
 */
class PayrollResultSearch extends PayrollResult
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'period_bulan', 'period_tahun', 'curr_amount'], 'number'],
            [['payroll_name', 'element_name'], 'safe'],
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

        // add conditions that should always apply here
        $query = PayrollResult::find()
            ->select([
                '{{payroll_result}}.*',
                'SUM({{payroll_result}}.curr_amount) AS sumReal'
            ])
            ->groupBy(['period_bulan', 'period_tahun']);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => [
                    'period_bulan' => SORT_ASC,
                    'period_tahun' => SORT_ASC,
                ]
            ],
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
            'period_bulan' => $this->period_bulan,
            'period_tahun' => $this->period_tahun,
            'curr_amount' => $this->curr_amount,
        ]);

        $query->andFilterWhere(['like', 'payroll_name', $this->payroll_name]);

        return $dataProvider;
    }


    public function search1($params)
    {

        $query1 = PayrollResult::find()->select([
            '{{payroll_result}}.*',
            'SUM({{payroll_result}}.curr_amount) AS sumReal'
        ])
            ->where(['period_bulan' => $params])
            ->groupBy(['element_name']);


        // add conditions that should always apply here
        $dataProvider1 = new ActiveDataProvider([
            'query' => $query1,
            'sort' => [
                'defaultOrder' => [
                    'period_bulan' => SORT_ASC,
                    'period_tahun' => SORT_ASC,
                    'element_name' => SORT_ASC
                ]
            ],
        ]);


//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//             $query->where('0=1');
//            return $dataProvider;
//        }

        return $dataProvider1;
    }
}
