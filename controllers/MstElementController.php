<?php

namespace reward\controllers;

use reward\models\ElementDetailSearch;
use reward\models\RewardLog;
use Yii;
use reward\models\MstElement;
use reward\models\MstElementSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MstElementController implements the CRUD actions for MstElement model.
 */
class MstElementController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MstElement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MstElementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MstElement model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

//        $searchModel = new ElementDetailSearch();
//
//        $dataProvider = $searchModel->search1(Yii::$app->request->queryParams);
//        $dataProvider->sort = ['defaultOrder' => ['band_individu'=>SORT_ASC]];
//        $dataProvider->query->where(['mst_element_id' => $id]);
//
//
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//            'dataProvider' => $dataProvider,
//        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MstElement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MstElement();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create a new Mst Element with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your mst element successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your mst element was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MstElement model.
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
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update Mst element with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your mst element successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your mst element was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MstElement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Mst Element with ID ".$id);
            Yii::$app->session->setFlash('success', "Your Mst Element successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your Mst Element was not deleted.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the MstElement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MstElement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MstElement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionLists(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                $cat_id = $parents[0];
                $out = MstElement::getOptionsbyElement($cat_id);
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }


}
