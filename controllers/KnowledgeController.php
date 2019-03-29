<?php

namespace reward\controllers;

use reward\models\RewardLog;
use Yii;
use reward\models\Knowledge;
use reward\models\KnowledgeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KnowledgeController implements the CRUD actions for Knowledge model.
 */
class KnowledgeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Knowledge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KnowledgeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Knowledge model.
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
     * Creates a new Knowledge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Knowledge();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create a new knowledge with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your knowledge successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your batch entry was not saved.");
            }

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Knowledge model.
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
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update knowledge with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your knowledge successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your knowledge was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Knowledge model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Knowledge with ID ".$id);
            Yii::$app->session->setFlash('success', "Your knowledge successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your knowledge was not deleted.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Knowledge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Knowledge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Knowledge::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
