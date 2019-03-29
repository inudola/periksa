<?php

namespace reward\controllers;

use reward\models\RewardLog;
use Yii;
use reward\models\Setting;
use reward\models\SettingSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends Controller
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
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_ASC]];
        $dataProvider->query->select(['group_nature'])->where(['not', ['group_nature' => null]])->distinct();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Setting model.
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
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create setting with ID " . $model->id);
                Yii::$app->session->setFlash('success', "Your setting successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your setting was not saved.");
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update setting with ID " . $model->id);
                Yii::$app->session->setFlash('success', "Your setting successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your setting was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Setting with ID " . $id);
            Yii::$app->session->setFlash('success', "Your setting successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your setting was not deleted.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionViewElement($groupId)
    {
        $model = Setting::find()->where(['group_nature' => $groupId])->one();

        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_ASC]];
        $dataProvider->query->where(['group_nature' => $groupId]);

        return $this->render('view-group', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model
        ]);
    }

    public function actionGenericWizard()
    {

        //base salaries
        $asumsiPoint = Setting::getBaseSetting(Setting::INDEX_ASUMSI_POINT);
        $totalPoint = Setting::getBaseSetting(Setting::INDEX_TOTAL_POINT);

        //perf.incentive
        $indexNkk = Setting::getBaseSetting(Setting::INDEX_NKK);
        $indexNki = Setting::getBaseSetting(Setting::INDEX_NKI);
        $indexNku = Setting::getBaseSetting(Setting::INDEX_NKU);

        //tax
        $tax = Setting::getBaseSetting(Setting::INDEX_TAX);

        //BPJS
        $maxJP = Setting::getBaseSetting(Setting::INDEX_JP_MAX);
        $maxJkes = Setting::getBaseSetting(Setting::INDEX_JKES_MAX);
        $iuranKes = Setting::getBaseSetting(Setting::IURAN_KES);
        $iuranJHT = Setting::getBaseSetting(Setting::IURAN_JHT);
        $iuranJP = Setting::getBaseSetting(Setting::IURAN_JP);
        $iuranJKK = Setting::getBaseSetting(Setting::IURAN_JKK);
        $iuranJKM = Setting::getBaseSetting(Setting::IURAN_JKM);

        //other allowances
        $indexTHR = Setting::getBaseSetting(Setting::INDEX_THR_1);
        $indexTHR2 = Setting::getBaseSetting(Setting::INDEX_THR_2);
        $indexTA = Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_AKHIR_TAHUN);
        $indexUangSakuAP = Setting::getBaseSetting(Setting::INDEX_UANG_SAKU_AKHIR_PROGRAM);
        $indexTunjCuti = Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_CUTI);

        return $this->render('wizard', [
            'asumsiPoint' => $asumsiPoint,
            'totalPoint' => $totalPoint,
            'indexNkk' => $indexNkk,
            'indexNki' => $indexNki,
            'indexNku' => $indexNku,
            'tax' => $tax,
            'maxJP' => $maxJP,
            'maxJkes' => $maxJkes,
            'iuranKes' => $iuranKes,
            'iuranJHT' => $iuranJHT,
            'iuranJP' => $iuranJP,
            'iuranJKK' => $iuranJKK,
            'iuranJKM' => $iuranJKM,
            'indexTHR' => $indexTHR,
            'indexTHR2' => $indexTHR2,
            'indexTA' => $indexTA,
            'indexUangSakuAP' => $indexUangSakuAP,
            'indexTunjCuti' => $indexTunjCuti
        ]);
    }
}
