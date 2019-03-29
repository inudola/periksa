<?php

namespace reward\controllers;

use reward\models\MstReward;
use reward\models\MstRewardSearch;
use reward\models\RewardCriteria;
use reward\models\RewardLog;
use reward\models\RewardSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * MstRewardController implements the CRUD actions for MstReward model.
 */
class MstRewardController extends Controller
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
                        'roles' => ['reward_admin'],
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
     * Lists all MstReward models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MstRewardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MstReward model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $searchModel = new RewardSearch();

        $dataProvider = $searchModel->search1(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['band_individu' => SORT_ASC]];
        $dataProvider->query->where(['mst_reward_id' => $id]);

        $rewardCriteria = RewardCriteria::instance()->findAll(['mst_reward_id' => $id]);


        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
            'rewardCriteria' => $rewardCriteria
        ]);
    }

    /**
     * Creates a new MstReward model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MstReward();

        if ($model->load(Yii::$app->request->post())) {


            $model->icon = UploadedFile::getInstance($model, 'icon');
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                if (isset($model->icon) && empty($model->file)) {
                    $model->icon->saveAs(Yii::getAlias('@reward/web/img/reward/') . $model->icon->baseName . '.' . $model->icon->extension);
                    $model->icon = 'img/reward/' . $model->icon->baseName . '.' . $model->icon->extension;

                    $model->file = '';
                } else if (isset($model->file) && empty($model->icon)) {
                    $model->file->saveAs(Yii::getAlias('@reward/web/file/') . $model->file->baseName . '.' . $model->file->extension);
                    $model->file = 'file/' . $model->file->baseName . '.' . $model->file->extension;
                    $model->icon = '';
                } else if (isset($model->file) && isset($model->icon)) {
                    $model->icon->saveAs(Yii::getAlias('@reward/web/img/reward/') . $model->icon->baseName . '.' . $model->icon->extension);
                    $model->icon = 'img/reward/' . $model->icon->baseName . '.' . $model->icon->extension;

                    $model->file->saveAs(Yii::getAlias('@reward/web/file/') . $model->file->baseName . '.' . $model->file->extension);
                    $model->file = 'file/' . $model->file->baseName . '.' . $model->file->extension;
                }


                if ($model->save()) {
                    //logging data
                    RewardLog::saveLog(Yii::$app->user->identity->username, "Create a Mst reward with ID " . $model->id);
                    Yii::$app->session->setFlash('success', "Your mst reward successfully created.");
                } else {
                    Yii::$app->session->setFlash('error', "Your mst reward was not saved.");
                }
                return $this->redirect(['index']);
            }
            return $this->redirect(['index']);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing MstReward model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $file = $model->icon;
        $pdf = $model->file;
        if (isset($_POST['MstReward'])) {
            $model->attributes = $_POST['MstReward'];
            $model->icon = $file;
            $model->file = $pdf;

            if (isset($_FILES['MstReward'])) {
                $model->icon = UploadedFile::getInstance($model, 'icon');
                $model->file = UploadedFile::getInstance($model, 'file');

                if (isset($model->icon) && empty($model->file)) {
                    $model->icon->saveAs(Yii::getAlias('@reward/web/img/reward/') . $model->icon->baseName . '.' . $model->icon->extension);
                    $model->icon = 'img/reward/' . $model->icon->baseName . '.' . $model->icon->extension;

                    $model->file = $pdf;
                } else if (isset($model->file) && empty($model->icon)) {
                    $model->file->saveAs(Yii::getAlias('@reward/web/file/') . $model->file->baseName . '.' . $model->file->extension);
                    $model->file = 'file/' . $model->file->baseName . '.' . $model->file->extension;

                    $model->icon = $file;
                } else if (isset($model->file) && isset($model->icon)) {
                    $model->icon->saveAs(Yii::getAlias('@reward/web/img/reward/') . $model->icon->baseName . '.' . $model->icon->extension);
                    $model->icon = 'img/reward/' . $model->icon->baseName . '.' . $model->icon->extension;

                    $model->file->saveAs(Yii::getAlias('@reward/web/file/') . $model->file->baseName . '.' . $model->file->extension);
                    $model->file = 'file/' . $model->file->baseName . '.' . $model->file->extension;
                }
            } else {
                $model->icon = $file;
                $model->file = $pdf;
            }


            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update mst reward with ID " . $model->id);
                Yii::$app->session->setFlash('success', "Your mst reward successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your mst reward was not saved.");
            }

            $this->redirect(array('view', 'id' => $model->id));

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MstReward model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public
    function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Mst Reward with ID " . $id);
            Yii::$app->session->setFlash('success', "Your mst reward successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your mst reward was not deleted.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the MstReward model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MstReward the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = MstReward::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public
    function actionPdf($id)
    {
        $model = MstReward::findOne($id);

        // This will need to be the path relative to the root of your app.
        $filePath = '/web';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@reward' . $filePath . '/' . $model->file);

        return Yii::$app->response->sendFile($completePath, $model->file);
    }
}
