<?php

namespace reward\controllers;

use reward\models\BatchDetail;
use reward\models\BatchDetailSearch;
use reward\models\BatchEntry;
use reward\models\BatchEntrySearch;
use reward\models\RewardLog;
use reward\models\Simulation;
use reward\models\SimulationDetail;
use reward\models\SimulationDetailSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BatchEntryController implements the CRUD actions for BatchEntry model.
 */
class BatchEntryController extends Controller
{

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
                        'roles' => ['reward_admin', 'reward_projection'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new BatchEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new BatchEntry();


        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create a new Batch Entry with ID " . $model->id);
                Yii::$app->session->setFlash('success', "Your batch entry successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your batch entry was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);

        }

        return $this->render('create', [
            'model' => $model,

        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update Batch Entry with ID " . $model->id);
                Yii::$app->session->setFlash('success', "Your batch entry successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your batch entry was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {

        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Batch Entry with ID " . $id);
            Yii::$app->session->setFlash('success', "Your batch entry successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your batch entry was not deleted.");
        }
        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
        //return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = BatchEntry::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    //tambah batch per bulan simulation
    public function actionAddBatch($simId, $bulan, $tahun, $mode)
    {

        $searchModel = new SimulationDetailSearch();

        //==========get nature==============///
        $getModel = $searchModel->search1(Yii::$app->request->queryParams);
        $getModel->sort = ['defaultOrder' => ['n_group' => SORT_ASC]];
        $getModel->query->where(['simulation_id' => $simId])
            ->andwhere(['bulan' => $bulan])
            ->andwhere(['tahun' => $tahun])
            ->andwhere(['keterangan' => $mode])
            ->andwhere(['not', ['n_group' => null]])
            ->andFilterWhere(['is', 'batch_id', new \yii\db\Expression('null')]);


        //==========get detail kenaikan element==============///
        $searchModel1 = new BatchDetailSearch();
        $dataProv = $searchModel1->search(Yii::$app->request->queryParams);
        $dataProv->sort = ['defaultOrder' => ['simulation_id' => SORT_DESC]];
        $dataProv->query->where(['simulation_id' => $simId])
            ->andwhere(['bulan' => $bulan])
            ->andwhere(['tahun' => $tahun])
            ->andwhere(['element' => 'JUMLAH NEW BI']);


        //==========get detail batch ==============///
        $dataProvider = $searchModel->search3(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['batch_id' => SORT_DESC, 'id' => SORT_DESC]];
        $dataProvider->query->where(['simulation_id' => $simId])
            ->andwhere(['bulan' => $bulan])
            ->andwhere(['tahun' => $tahun])
            ->andwhere(['keterangan' => $mode])
            ->andWhere(['not', ['batch_id' => null]]);

        //==========set batch entry==============///
        $model1 = new SimulationDetail();
        $model = new BatchEntry();


        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model1->load(Yii::$app->request->post());

            $cmd1 = Yii::getAlias('@webroot') . '/../../yii simul/add "' . $model->type_id . '" "' . $model->jumlah_orang . '" "' . $model->bi . '" "' . $model->bp . '" "' . $model->bp_tujuan . '" "' . $model->perc_inc_gadas . '" "' . $model->perc_inc_tbh . '" "' . $model->perc_inc_rekomposisi . '" "' . $model1->simulation_id . '" "' . $model1->bulan . '" "' . $model1->tahun . '" "' . $model1->keterangan . '"';
            $output = shell_exec($cmd1);

            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Create a new Batch Entry (new recruitment) with Type " . $model->mstType->type);

            Yii::$app->session->setFlash('success', "Your data successfully created.");
            return $this->redirect(['add-batch', 'simId' => $simId, 'bulan' => $bulan, 'tahun' => $tahun, 'mode' => $mode]);

        }


        return $this->render('add', [
            'model' => $model,
            'model1' => $model1,
            'getModel' => $getModel,
            'getBatch' => $getBatch,
            'dataProvider' => $dataProvider,
            'dataProv' => $dataProv,
            'searchModel' => $searchModel,
            'mode' => $mode
        ]);

    }


    public function actionViewGroup($simId, $bulan, $tahun, $group)
    {

        $searchModel = new SimulationDetailSearch();

        //==========get element==============///
        $getModel = $searchModel->search(Yii::$app->request->queryParams);
        $getModel->sort = ['defaultOrder' => ['element' => SORT_ASC]];
        $getModel->query->where(['simulation_id' => $simId])
            ->andwhere(['bulan' => $bulan])
            ->andwhere(['tahun' => $tahun])
            ->andwhere(['n_group' => $group])
            ->andwhere(['not', ['n_group' => null]])
            ->andFilterWhere(['is', 'batch_id', new \yii\db\Expression('null')]);

        return $this->render('//simulation-detail/view-group', [
            'getModel' => $getModel,
            'searchModel' => $searchModel,
            'group' => $group
        ]);

    }


    public
    function actionCreateBatch($simId)
    {
        $getModel = SimulationDetail::find()->where(['simulation_id' => $simId])
            ->andwhere(['IS NOT', 'batch_id', null])
            ->all();

        $model1 = new SimulationDetail();
        $model = new BatchEntry();
        $models = [new BatchEntry()];


        if (($model->load(Yii::$app->request->post())) && ($model1->load(Yii::$app->request->post()))) {

            $model->setCreateBatch($simId);

            RewardLog::saveLog(Yii::$app->user->identity->username, "Create a new Batch Entry (new element) with ID " . $model->id);

            Yii::$app->session->setFlash('success', "Your data successfully created.");
            return $this->redirect(['//simulation/index']);
        }


        return $this->render('create', [
            'model' => $model,
            'model1' => $model1,
            'getModel' => $getModel,
            'models' => $models,
        ]);
    }


    public
    function actionGenerateSaldo($simId)
    {

        $simId = Yii::$app->getRequest()->getQueryParam('simId');

        if (!empty($simId)) {
            Yii::$app->getSession()->getFlash('error');
            if (Yii::$app->request->isPost) {

                $cmd = Yii::getAlias('@webroot') . '/../../yii simul/saldo "' . $simId . '"';
                $output = shell_exec($cmd);

                RewardLog::saveLog(Yii::$app->user->identity->username, "Regenerate Simulation with ID " . $simId);

                Yii::$app->session->setFlash('success', "Your data successfully created.");
                return $this->redirect(['//simulation/index']);
            }
        }

        return $this->redirect(['//simulation/index']);

    }


}
