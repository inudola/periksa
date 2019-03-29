<?php

namespace reward\controllers;

use reward\models\MstReward;
use reward\models\Reward;
use reward\models\RewardCriteria;
use reward\models\RewardLog;
use reward\models\RewardSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RewardController implements the CRUD actions for Reward model.
 */
class RewardController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public static function allowedDomains()
    {
        return [
            // '*',                        // star allows all domains
            'http://test1.example.com',
            'http://test2.example.com',
        ];
    }
     
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
     * Lists all Reward models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RewardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Reward model.
     * @param string $id
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
     * Creates a new Reward model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model       = new Reward();

         $models = [new Reward()];

         if ($model->load(Yii::$app->request->post()) ) {

             $xx = Yii::$app->request->post();

             // cari maks jenis reward yg dikirim
             $mentok = false;
             $highestIndex = 0;
             $currentIndex = 0;
             $successSave = false;
             $newRewardIds = [];
             while(!$mentok) {
                 if (isset($xx['Reward'][$currentIndex])) {
                     $highestIndex = $currentIndex;

                     $band_individu = $xx['Reward'][$currentIndex]['band_individu'];
                     $band_position = $xx['Reward'][$currentIndex]['band_position'];
                     $band = $xx['Reward'][$currentIndex]['band'];
                     $amount = $xx['Reward'][$currentIndex]['amount'];
                     $newReward = new Reward();
                     $newReward->load($xx);
                     $newReward->band_individu = $band_individu;
                     $newReward->band_position = $band_position;
                     $newReward->band = $band;
                     $newReward->amount = str_replace(",", "", $amount);

                     if ($newReward->save()) {
                         //logging data
                         RewardLog::saveLog(Yii::$app->user->identity->username, "Created reward with ID ".$newReward->id);

                     }
                     $newRewardIds[] = $newReward->id;

                     $currentIndex++;
                     $successSave = true;
                 } else {
                     $mentok = true;
                 }
             }


             Yii::$app->getSession()->setFlash('success','Data berhasil disimpan');
             return $this->redirect(['/mst-reward/view', 'id' => $model->mst_reward_id]);

        }

        //ambil parameter mst reward id
        $params = Yii::$app->request->get('mst_reward_id');

        $reward      = MstReward::find()->select('reward_name')->where(['id' => $params])->all();
        //$rewardCriteria = RewardCriteria::instance()->findAll(['mst_reward_id' => $params]);

        return $this->render('create', [
            'model'     => $model,
            'models'    => $models,
            'params'    => $params,
            'reward'    => $reward,
            //'rewardCriteria' => $rewardCriteria
        ]);
    }

    /**
     * Updates an existing Reward model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model      = $this->findModel($id);

        //$params = Yii::$app->request->get('mst_reward_id');
        $rewardCriteria = RewardCriteria::instance()->findAll(['mst_reward_id' => $id]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update Reward with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your reward successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your reward was not saved.");
            }

            return $this->redirect(['/mst-reward/view', 'id' => $model->mst_reward_id]);
        }

        return $this->render('update', [
            'model'     => $model,
            'rewardCriteria' => $rewardCriteria
        ]);

    }

    /**
     * Deletes an existing Reward model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        if ($model->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Reward with ID ".$id);
            Yii::$app->session->setFlash('success', "Your reward successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your reward was not deleted.");
        }

        return $this->redirect(['/mst-reward/view', 'id' => $model->mst_reward_id]);
    }
    

    /**
     * Finds the Reward model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Reward the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reward::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
