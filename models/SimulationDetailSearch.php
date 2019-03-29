<?php

namespace reward\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SimulationDetailSearch represents the model behind the search form of `projection\models\SimulationDetail`.
 */
class SimulationDetailSearch extends SimulationDetail
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

    /*public function search($params)
    {

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("
    SELECT 
    t2.bulan, 
	 t2.tahun, 
    Sum(amount) AS total_proj, 
    t3.amountReal

FROM simulation_detail AS t2

    INNER JOIN
        (
        SELECT period_bulan, period_tahun,
               SUM(curr_amount) AS amountReal
        FROM payroll_result
        GROUP BY period_bulan, period_tahun
        ) AS t3
    ON t2.tahun = t3.period_tahun 
    
    WHERE t2.bulan = t3.period_bulan
	 AND simulation_id = '$params'
	 
	 GROUP BY bulan, tahun");

        $query = $command->queryAll();


        return $query;
    }*/

    public function search($params)
    {
        $query = SimulationDetail::find();

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


        return $dataProvider;
    }
    public function search11($params)
    {


        $query = SimulationDetail::find()
            ->select([
                '{{simulation_detail}}.*',
                'SUM({{simulation_detail}}.amount) AS sumProj',
            ])
            ->where(['NOT', ['n_group' => null]])
            ->groupBy(['bulan', 'tahun']);

        // add conditions that should always apply her  e
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => [
                    //'simulation_group' => SORT_ASC,
                    'tahun' => SORT_ASC,
                    'bulan' => SORT_ASC,
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

        $query1 = SimulationDetail::find()
            ->select([
                '{{simulation_detail}}.*', // select all fields
                'SUM({{simulation_detail}}.amount) AS my_sum' // calculate orders count
            ])
            ->where(['NOT', ['n_group' => null]])
            ->groupBy('n_group');

        // add conditions that should always apply here
        $dataProvider1 = new ActiveDataProvider([
            'query' => $query1,
//            'pagination' => [
//                'pageSize' => 30,
//            ],
            'sort' => [
                'defaultOrder' => [
                    'bulan' => SORT_ASC,
                    'n_group' => SORT_ASC
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

        // grid filtering conditions
        $query1->andFilterWhere([
            'id' => $this->id,
            'simulation_id' => $this->simulation_id,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'amount' => $this->amount,
            'batch_id' => $this->batch_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query1->andFilterWhere(['like', 'element', $this->element]);

        return $dataProvider1;
    }

    public function search3($params)
    {
        $query = SimulationDetail::find()->groupBy('batch_id');

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


        return $dataProvider;
    }
}
