<?php

namespace reward\controllers;

use reward\models\RewardLog;
use Yii;
use reward\models\RewardCriteria;
use reward\models\RewardCriteriaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RewardCriteriaController implements the CRUD actions for RewardCriteria model.
 */
class RewardCriteriaController extends Controller
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
     * Lists all RewardCriteria models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RewardCriteriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RewardCriteria model.
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
     * Creates a new RewardCriteria model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new RewardCriteria();

        if ($model->load(Yii::$app->request->post())) {

            foreach ($model->criteria_name as $criteria) {
                $criterias = new RewardCriteria();
                $criterias->setAttributes([
                    'criteria_name' => $criteria,
                    'mst_reward_id' => $model->mst_reward_id,
                ]);


                $criterias->save();

                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create Reward criteria with ID ".$model->id);

            }
            Yii::$app->getSession()->setFlash('success', 'Data berhasil disimpan');
            return $this->redirect(['mst-reward/view', 'id' => $model->mst_reward_id]);
        }

        //ambil parameter mst reward id
        $params = Yii::$app->request->get('mst_reward_id');
        $criteria = RewardCriteria::findAll(['mst_reward_id' => $params]);

        return $this->render('create', [
            'model' => $model,
            'criteria' => $criteria,
            'params' => $params
        ]);
    }

    /**
     * Updates an existing RewardCriteria model.
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
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update Reward criteria with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your reward criteria successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your reward criteria was not saved.");
            }
            return $this->redirect(['/mst-reward/view', 'id' => $model->mst_reward_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RewardCriteria model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {

            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Reward Criteria with ID ".$id);
            Yii::$app->session->setFlash('success', "Your reward criteria successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your reward criteria was not deleted.");
        }

        return $this->redirect(['mst-reward/view', 'id' => $model->mst_reward_id]);
    }

    /**
     * Finds the RewardCriteria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RewardCriteria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RewardCriteria::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
