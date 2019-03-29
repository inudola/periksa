<?php

namespace reward\controllers;

use common\models\LoginForm;
use kartik\icons\IcoFontAsset;
use reward\models\PayrollResult;
use reward\models\PayrollResultSearch;
use reward\models\Simulation;
use reward\models\SimulationDetail;
use reward\models\SimulationDetailSearch;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;


/**
 * Site controller
 */
class MonitoringController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['reward_admin','reward_projection'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $lastProjection = SimulationDetail::find()->orderBy(['simulation_id' => SORT_DESC])->one();
        //$lastRealization    = PayrollResult::find()->orderBy(['period_tahun' => SORT_DESC])->one();

        $getSimulation = Simulation::find()->orderBy(['id' => SORT_DESC])->one();
        //$tahunSimulation    = date("Y", strtotime($getSimulation->start_date));
        $startMonthSimulation = date("n", strtotime($getSimulation->start_date));
        $endMonthSimulation = date("n", strtotime($getSimulation->end_date));


        //get current year
        $yearNow = date('Y-m-d');
        $currentYear = intval(date('Y', strtotime($yearNow)));


        //get master bulan
        $rows = SimulationDetail::getBulan();

        /*get data for graphic*/
//        $rows = (new \yii\db\Query())
//            ->select(['bulan'])
//            ->from('simulation_detail')
//            ->join('LEFT JOIN', 'payroll_result', 'payroll_result.period_bulan = simulation_detail.bulan')
//            ->where(['simulation_id' => $lastProjection])
//            ->groupBy(['bulan'])
//            ->column();


        $rowsa = (new \yii\db\Query())
            ->select(['SUM(amount) AS cnt'])
            ->from('simulation_detail')
            ->where(['simulation_id' => $lastProjection])
            ->andWhere(['NOT', ['n_group' => null]])
            ->groupBy(['bulan'])
            ->column();

        $rowsaa = (new \yii\db\Query())
            ->select(['SUM(curr_amount) AS cnt'])
            ->from('payroll_result')
            ->where(['period_tahun' => $currentYear])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation])
            ->groupBy(['period_bulan'])
            ->column();


        //$rows       = array_map('floatval', $rows);

        $rowsa = array_map('floatval', $rowsa);
        $rowsaa = array_map('floatval', $rowsaa);

//        $data['bulan']      = json_encode($rows);
//        $data['projection'] = json_encode($rowsa);
//        $data['realization'] = json_encode($rowsaa);

        $data['bulan'] = $rows;
        $data['projection'] = $rowsa;
        $data['realization'] = $rowsaa;


        /*=============get total projection==============*/
        $queryProjection = SimulationDetail::find()->where(['simulation_id' => $lastProjection])->andWhere(['NOT', ['n_group' => null]]);
        $sumProjection = $queryProjection->sum('amount');

        /*=============get total realization==============*/
        $queryRealization = PayrollResult::find()->where(['period_tahun' => $currentYear]);
        $sumRealization = $queryRealization->sum('curr_amount');

        /*=============get total gap==============*/
        $gap = $sumProjection - $sumRealization;

        /*Dropdownlist history projection*/
        $simulation = Simulation::getListSimulations();

        $searchModel = new SimulationDetailSearch();
        $dataProvider = $searchModel->search11(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sumProjection' => $sumProjection,
            'sumRealization' => $sumRealization,
            'list' => $simulation,
            'gap' => $gap,
            'query' => $lastProjection,
            'data' => $data,
            'currentYear' => $currentYear
        ]);
    }


    public function actionView($id)
    {
        return $this->render('//simulation-detail/view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('//simulation-detail/update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = SimulationDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*Get data simultion based on filter simulation id*/
    public function actionGetDataTabel($id, $mode)
    {

        $lastProjection = SimulationDetail::find()->where(['simulation_id' => $id])->one();
        $lastRealization = PayrollResult::find()->where(['resource' => 'Revex'])->orderBy(['period_tahun' => SORT_DESC])->one();

        $getSimulation = Simulation::find()->where(['id' => $id])->one();
        $tahunAwalSimulation = intval(date("Y", strtotime($getSimulation->start_date)));
        $tahunAkhirSimulation = intval(date("Y", strtotime($getSimulation->end_date)));
        $startMonthSimulation = date("n", strtotime($getSimulation->start_date));
        $endMonthSimulation = date("n", strtotime($getSimulation->end_date));

        //get current year
        $yearNow = date('Y-m-d');
        $currentYear = intval(date('Y', strtotime($yearNow)));

        $currentReal = intval($lastRealization->period_tahun);


        //get master bulan
        //$rows = SimulationDetail::getBulan();

        //check alternatif
        $findAlt = SimulationDetail::find()
            ->select('keterangan')
            ->where(['simulation_id' => $id])
            ->andWhere(['not', ['keterangan' => 'ORIGINAL BUDGET']])
            ->asArray()->all();


        /*====================get data for graphic start===================*/
        $rows = (new \yii\db\Query())
            ->select(['bulan'])
            ->from('simulation_detail')
            ->where(['simulation_id' => $id])
            ->groupBy(['bulan', 'tahun'])
            ->orderBy([
                'tahun' => SORT_ASC,
                'bulan' => SORT_ASC,
            ])
            ->column();


        $rowsa = (new \yii\db\Query())
            ->select(['SUM(amount) AS cnt'])
            ->from('simulation_detail')
            ->where(['simulation_id' => $id])
            ->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])
            ->andFilterWhere(['keterangan' => 'ORIGINAL BUDGET'])
            ->andWhere(['NOT', ['n_group' => null]])
            ->groupBy(['bulan', 'tahun'])
            ->orderBy([
                'tahun' => SORT_ASC,
                'bulan' => SORT_ASC,
            ])
            ->column();

        $rowsaa = (new \yii\db\Query())
            ->select(['SUM(curr_amount) AS cnt'])
            ->from('payroll_result')
            //->join('LEFT JOIN', 'simulation_detail', 'payroll_result.period_bulan = simulation_detail.bulan')
            ->where(['period_tahun' => $currentReal])
            ->andWhere(['resource' => 'Revex'])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation])
            ->groupBy(['period_bulan'])
            ->column();


        $rowsaaa = (new \yii\db\Query())
            ->select(['SUM(amount) AS cnt'])
            ->from('simulation_detail')
            ->where(['simulation_id' => $id])
            //->andWhere(['not', ['parent_id' => null]])
            ->andFilterWhere(['IN', 'keterangan' , [$mode, 'ORIGINAL BUDGET']])
            ->andWhere(['NOT', ['n_group' => null]])
            ->groupBy(['bulan', 'tahun'])
            ->orderBy([
                'tahun' => SORT_ASC,
                'bulan' => SORT_ASC,
            ])
            ->column();



        $rows = array_map('floatval', $rows);
        $rowsa = array_map('floatval', $rowsa);
        $rowsaa = array_map('floatval', $rowsaa);
        $rowsaaa = array_map('floatval', $rowsaaa);


        $data['bulan'] = array_values($rows);
        $data['projection'] = array_values($rowsa);

        if (!empty($mode)) {
            $data['alternatif'] = array_values($rowsaaa);
        } else {
            $data['alternatif'] = [];
        }
        if ($tahunAkhirSimulation == $currentReal) {
            $data['realization'] = array_values($rowsaa);
        } else {
            $data['realization'] = [];
        }


        /*====================get data for graphic start===================*/


        /*====================get total data start=========================*/
        //original
        $queryProjection = SimulationDetail::find()->where(['simulation_id' => $id])->andWhere(['NOT', ['n_group' => null]])
            ->andFilterWhere(['keterangan' => 'ORIGINAL BUDGET']);

        //alternatif
        $queryAlt = SimulationDetail::find()->where(['simulation_id' => $id])->andWhere(['NOT', ['n_group' => null]])
            ->andWhere(['IN', 'keterangan' , [$mode, 'ORIGINAL BUDGET']]);

        $sumAlternatif = $queryAlt->sum('amount');

        $queryRealization = PayrollResult::find()
            ->where(['period_tahun' => $currentReal])
            ->andWhere(['resource' => 'Revex'])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation]);
        $sumRealization = $queryRealization->sum('curr_amount');
        $sumProjection = $queryProjection->sum('amount');


        $gap = $sumProjection - $sumRealization;
        /*====================get total data end=========================*/


        /*====================get data simulation start=========================*/
        $searchModel = new SimulationDetailSearch();
        $dataProvider = $searchModel->search11(Yii::$app->request->queryParams);
            //$dataProvider->sort = ['defaultOrder' => ['tahun'=>SORT_ASC]];
        $dataProvider->query->where(['simulation_id' => $id])->andWhere(['NOT', ['n_group' => null]])
            ->andFilterWhere(['keterangan' => 'ORIGINAL BUDGET']);
        /*====================get data simulation end=========================*/


        /*====================get data alternatif start=========================*/
        $searchModel = new SimulationDetailSearch();
        $dataProviderAlt = $searchModel->search11(Yii::$app->request->queryParams);
        $dataProviderAlt->sort = ['defaultOrder' => ['tahun' => SORT_ASC, 'bulan' => SORT_ASC]];
        $dataProviderAlt->query->where(['simulation_id' => $id])->andWhere(['NOT', ['n_group' => null]])
            ->andWhere(['IN', 'keterangan' , [$mode, 'ORIGINAL BUDGET']]);
        /*====================get data simulation end=========================*/


        /*====================get data realization start=========================*/
        $queryReal = PayrollResult::find()
            ->select([
                '{{payroll_result}}.*', // select all customer fields
                'SUM({{payroll_result}}.curr_amount) AS sumReal' // calculate orders count
            ])
            ->where(['period_tahun' => $currentReal])
            ->andWhere(['resource' => 'Revex'])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation])
            ->groupBy(['period_bulan']);

        if (($tahunAwalSimulation || $tahunAkhirSimulation) == $currentReal) {
            $dataProviderReal = new ActiveDataProvider([
                'query' => $queryReal,
                // 'pagination' => [
                //     'pageSize' => 12
                // ]
            ]);
        } else {
            $dataProviderReal = [];
        }
        /*====================get data realization end=========================*/

        return $this->renderAjax('_dataSimulation', [
            'dataProvider' => $dataProvider,
            'dataProviderAlt' => $dataProviderAlt,
            'dataProviderReal' => $dataProviderReal,
            'sumProjection' => $sumProjection,
            'sumAlternatif' => $sumAlternatif,
            'sumRealization' => $sumRealization,
            'gap' => $gap,
            'lastProjection' => $lastProjection,
            'query' => $lastProjection,
            'data' => $data,
            'currentReal' => $currentReal,
            'tahunAwalSimulation' => $tahunAwalSimulation,
            'tahunAkhirSimulation' => $tahunAkhirSimulation,
            'mode' => $mode,
            'findAlt' => $findAlt
        ]);
    }


    public function actionGetAlternatif()
    {
        $data = [];
        $request = Yii::$app->request;
        $obj = $request->post('obj');
        $value = $request->post('value');
        switch ($obj) {
            case 'list':
                $data = SimulationDetail::find()->where(['simulation_id' => $value])
                    ->andWhere(['not', ['keterangan' => null]])
                    ->andWhere(['not', ['keterangan' => 'ORIGINAL BUDGET']])->all();
                break;
        }

        if (!empty($data)) {
            $tagOptions = ['prompt' => "=== Select Alternatif ==="];
        } else {
            $tagOptions = ['prompt' => "No result"];
        }
        return Html::renderSelectOptions([], ArrayHelper::map($data, 'simulation_id', 'keterangan', 'keterangan'), $tagOptions);
    }


    /**
     * Displays compare.
     *
     * @return mixed
     */
    public function actionCompare()
    {
        $lastProjection = SimulationDetail::find()->orderBy(['simulation_id' => SORT_DESC])->one();
        //$lastRealization    = PayrollResult::find()->orderBy(['period_tahun' => SORT_DESC])->one();

        $getSimulation = Simulation::find()->orderBy(['id' => SORT_DESC])->one();
        //$tahunSimulation    = date("Y", strtotime($getSimulation->start_date));
        $startMonthSimulation = date("n", strtotime($getSimulation->start_date));
        $endMonthSimulation = date("n", strtotime($getSimulation->end_date));


        //get current year
        $yearNow = date('Y-m-d');
        $currentYear = intval(date('Y', strtotime($yearNow)));


        //get master bulan
        $rows = SimulationDetail::getBulan();

        /*get data for graphic*/
//        $rows = (new \yii\db\Query())
//            ->select(['bulan'])
//            ->from('simulation_detail')
//            ->join('LEFT JOIN', 'payroll_result', 'payroll_result.period_bulan = simulation_detail.bulan')
//            ->where(['simulation_id' => $lastProjection])
//            ->groupBy(['bulan'])
//            ->column();


        $rowsa = (new \yii\db\Query())
            ->select(['SUM(amount) AS cnt'])
            ->from('simulation_detail')
            ->where(['simulation_id' => $lastProjection])
            ->andWhere(['NOT', ['n_group' => null]])
            ->groupBy(['bulan'])
            ->column();

        $rowsaa = (new \yii\db\Query())
            ->select(['SUM(curr_amount) AS cnt'])
            ->from('payroll_result')
            ->where(['period_tahun' => $currentYear])
            ->andWhere(['resource' => 'Revex'])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation])
            ->groupBy(['period_bulan'])
            ->column();


        //$rows       = array_map('floatval', $rows);

        $rowsa = array_map('floatval', $rowsa);
        $rowsaa = array_map('floatval', $rowsaa);

//        $data['bulan']      = json_encode($rows);
//        $data['projection'] = json_encode($rowsa);
//        $data['realization'] = json_encode($rowsaa);

        $data['bulan'] = $rows;
        $data['projection'] = $rowsa;
        $data['realization'] = $rowsaa;


        /*=============get total projection==============*/
        $queryProjection = SimulationDetail::find()->where(['simulation_id' => $lastProjection])->andWhere(['NOT', ['n_group' => null]]);
        $sumProjection = $queryProjection->sum('amount');

        /*=============get total realization==============*/
        $queryRealization = PayrollResult::find()->where(['period_tahun' => $currentYear])->andWhere(['resource' => 'Revex']);
        $sumRealization = $queryRealization->sum('curr_amount');

        /*=============get total gap==============*/
        $gap = $sumProjection - $sumRealization;

        /*Dropdownlist history projection*/
        $simulation = Simulation::getListSimulations();

        $searchModel = new SimulationDetailSearch();
        $dataProvider = $searchModel->search11(Yii::$app->request->queryParams);

        return $this->render('compare', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sumProjection' => $sumProjection,
            'sumRealization' => $sumRealization,
            'list' => $simulation,
            'gap' => $gap,
            'query' => $lastProjection,
            'data' => $data,
            'currentYear' => $currentYear
        ]);
    }

    /*Get data simultion based on filter simulation id*/
    public function actionGetData($id, $mode)
    {

        $lastProjection = SimulationDetail::find()->where(['simulation_id' => $id])->one();
        $lastRealization = PayrollResult::find()->where(['resource' => 'Revex'])->orderBy(['period_tahun' => SORT_DESC])->one();

        $getSimulation = Simulation::find()->where(['id' => $id])->one();
        $tahunAwalSimulation = intval(date("Y", strtotime($getSimulation->start_date)));
        $tahunAkhirSimulation = intval(date("Y", strtotime($getSimulation->end_date)));
        $startMonthSimulation = date("n", strtotime($getSimulation->start_date));
        $endMonthSimulation = date("n", strtotime($getSimulation->end_date));

        //get current year
        $yearNow = date('Y-m-d');
        $currentYear = intval(date('Y', strtotime($yearNow)));

        $currentReal = intval($lastRealization->period_tahun);


        /*====================get data for graphic start===================*/
        $rows = (new \yii\db\Query())
            ->select(['bulan'])
            ->from('simulation_detail')
            ->where(['simulation_id' => $id])
            ->groupBy(['bulan', 'tahun'])
            ->orderBy([
                'tahun' => SORT_ASC,
                'bulan' => SORT_ASC,
            ])
            ->column();


        $rowsa = (new \yii\db\Query())
            ->select(['SUM(amount) AS cnt'])
            ->from('simulation_detail')
            ->where(['simulation_id' => $id])
            ->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])
            ->andWhere(['NOT', ['n_group' => null]])
            ->andFilterWhere(['keterangan' => 'ORIGINAL BUDGET'])
            ->groupBy(['bulan', 'tahun'])
            ->orderBy([
                'tahun' => SORT_ASC,
                'bulan' => SORT_ASC,
            ])
            ->column();

        $rowsaa = (new \yii\db\Query())
            ->select(['SUM(curr_amount) AS cnt'])
            ->from('payroll_result')
            //->join('LEFT JOIN', 'simulation_detail', 'payroll_result.period_bulan = simulation_detail.bulan')
            ->where(['period_tahun' => $currentReal])
            ->andWhere(['resource' => 'Revex'])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation])
            ->groupBy(['period_bulan'])
            ->column();



        $rows = array_map('floatval', $rows);
        $rowsa = array_map('floatval', $rowsa);
        $rowsaa = array_map('floatval', $rowsaa);



        $data['bulan'] = array_values($rows);
        $data['projection'] = array_values($rowsa);


        if ($tahunAkhirSimulation == $currentReal) {
            $data['realization'] = array_values($rowsaa);
        } else {
            $data['realization'] = [];
        }


        /*====================get data for graphic start===================*/


        /*====================get total data start=========================*/
        //original
        $queryProjection = SimulationDetail::find()->where(['simulation_id' => $id])->andWhere(['NOT', ['n_group' => null]])
            ->andFilterWhere(['keterangan' => 'ORIGINAL BUDGET']);


        $queryRealization = PayrollResult::find()
            ->where(['period_tahun' => $currentReal])
            ->andWhere(['resource' => 'Revex'])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation]);

        $sumRealization = $queryRealization->sum('curr_amount');
        $sumProjection = $queryProjection->sum('amount');


        $gap = $sumProjection - $sumRealization;
        /*====================get total data end=========================*/


        /*====================get data simulation start=========================*/
        $searchModel = new SimulationDetailSearch();
        $dataProvider = $searchModel->search11(Yii::$app->request->queryParams);
        //$dataProvider->sort = ['defaultOrder' => ['tahun'=>SORT_ASC]];
        $dataProvider->query->where(['simulation_id' => $id])->andWhere(['NOT', ['n_group' => null]])
            ->andFilterWhere(['keterangan' => 'ORIGINAL BUDGET']);
        /*====================get data simulation end=========================*/



        /*====================get data realization start=========================*/
        $queryReal = PayrollResult::find()
            ->select([
                '{{payroll_result}}.*', // select all customer fields
                'SUM({{payroll_result}}.curr_amount) AS sumReal' // calculate orders count
            ])
            ->where(['period_tahun' => $currentReal])
            ->andWhere(['resource' => 'Revex'])
            ->andWhere(['between', 'period_bulan', $startMonthSimulation, $endMonthSimulation])
            ->groupBy(['period_bulan']);

        if (($tahunAwalSimulation || $tahunAkhirSimulation) == $currentReal) {
            $dataProviderReal = new ActiveDataProvider([
                'query' => $queryReal,
                // 'pagination' => [
                //     'pageSize' => 12
                // ]
            ]);
        } else {
            $dataProviderReal = [];
        }
        /*====================get data realization end=========================*/

        return $this->renderAjax('_data', [
            'dataProvider' => $dataProvider,
            'dataProviderReal' => $dataProviderReal,
            'sumProjection' => $sumProjection,
            'sumRealization' => $sumRealization,
            'gap' => $gap,
            'lastProjection' => $lastProjection,
            'query' => $lastProjection,
            'data' => $data,
            'currentReal' => $currentReal,
            'tahunAwalSimulation' => $tahunAwalSimulation,
            'tahunAkhirSimulation' => $tahunAkhirSimulation,
            'mode' => $mode,
        ]);
    }


}
