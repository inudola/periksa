<?php

namespace reward\controllers;

use reward\models\RewardLog;
use reward\models\SimulationDetail;
use reward\models\SimulationDetailSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SimulationDetailController implements the CRUD actions for SimulationDetail model.
 */
class SimulationDetailController extends Controller
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

    /**
     * Lists all SimulationDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data = SimulationDetail::find()
            ->select(['SUM(amount) AS cnt'])
            //->where('approved = 1')
            ->groupBy(['bulan'])
            ->all();

        $sumProjection = SimulationDetail::find()
            //->where(['tahun'=>'2019'])
            ->sum('amount');

        $searchModel = new SimulationDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ddiagram' => $data,
            'sumProjection' => $sumProjection
        ]);
    }

    /**
     * Displays a single SimulationDetail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Displays a single SimulationDetail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewAsli($id)
    {
        return $this->render('view-asli', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SimulationDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SimulationDetail();
        $people = Yii::$app->user->identity->employee;

        if ($model->load(Yii::$app->request->post())) {

            $model->setAttributes([
                'simulation_id' => $model->simulation_id,
                'bulan' => $model->bulan,
                'tahun' => $model->tahun,
                'element' => $model->element,
                'amount' => $model->amount,
                'n_group' => $model->n_group,
                'created_by' => $people->person_id
            ]);

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Add element with ID: " . $model->id.", Bulan: ".$model->bulan.", Tahun: ".$model->tahun);
                Yii::$app->session->setFlash('success', "Your element successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your element was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
            //return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing SimulationDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model1 = new SimulationDetail();
        $people = Yii::$app->user->identity->employee;

        if ($model->load(Yii::$app->request->post())) {
            //&& $model->save();

            $model1->setAttributes([
                'simulation_id' => $model->simulation_id,
                'bulan' => $model->bulan,
                'tahun' => $model->tahun,
                'element' => $model->element,
                'amount' => $model->amount,
                'parent_id' => $model->parent_id,
                'batch_id' => $model->batch_id,
                'keterangan' => $model->keterangan,
                'updated_by' => $people->person_id
            ]);

            if ($model1->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Clone Simulation with ID " . $id . "to " . $model->keterangan);
                Yii::$app->session->setFlash('success', "Your simulation successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your simulation was not saved.");
            }

            return $this->redirect(['/batch-entry/add-batch',
                'simId' => $model->simulation_id,
                'bulan' => $model->bulan,
                'tahun' => $model->tahun,
                'mode' => $model->keterangan
                ]);
        }

        return $this->render('update', [
            'model' => $model,

        ]);
    }

    /**
     * Deletes an existing SimulationDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Simulation Detail with ID " . $id);
            Yii::$app->session->setFlash('success', "Your simulation detail successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your simulation detail was not deleted.");
        }
        //return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
        return $this->redirect(['/simulation/index']);
    }

    /**
     * Finds the SimulationDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SimulationDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimulationDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionDel($simId, $bulan, $tahun, $batch)
    {

        $delete = Yii::$app->db->createCommand("
            DELETE FROM simulation_detail 
            WHERE simulation_id = '$simId' 
            AND bulan = '$bulan' 
            AND tahun = '$tahun'
            AND batch_id = '$batch'
            ")->execute();

        if ($delete) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Simulation Detail with Simulation ID " . $simId . ",Bulan " . $bulan . ",Tahun " . $tahun . ",and Batch Id " . $batch);
            Yii::$app->session->setFlash('success', "Your data successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your data was not deleted.");
        }
        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
    }


    public function actionViewBatchDetail($simId, $bulan, $tahun, $batch)
    {


        $model = SimulationDetail::find()->where(['batch_id' => $batch])->one();

        $searchModel = new SimulationDetailSearch();
        $dataProvider = $searchModel->search1(Yii::$app->request->queryParams);
        //$dataProvider->sort = ['defaultOrder' => ['band_individu'=>SORT_ASC]];
        $dataProvider->query->where(['simulation_id' => $simId])
            ->andwhere(['bulan' => $bulan])
            ->andwhere(['tahun' => $tahun])
            ->andwhere(['batch_id' => $batch]);


        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model
        ]);
    }


}
