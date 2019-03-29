<?php

namespace reward\controllers;

use reward\models\AuthAssignment;
use reward\models\AuthAssignmentSearch;
use reward\models\RewardLog;
use reward\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AuthAssignmentController implements the CRUD actions for AuthAssignment model.
 */
class AuthAssignmentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'except' => ['index'],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['sysadmin'],
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
     * Lists all AuthAssignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthAssignmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthAssignment model.
     * @param string $item_name
     * @param string $user_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($item_name, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($item_name, $user_id),
        ]);
    }

    /**
     * Creates a new AuthAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthAssignment();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            // search existing user
            $theUsers = User::find()->where(['username' => $model->user_id]);
            if ($theUsers->count() <= 0) {
                // user does not exist in User table
                $newUser = new User();
                $newUser->nik = '';
                $newUser->id = $model->user_id;
                $newUser->username = $model->user_id;
                $newUser->email = $model->user_id.'@telkomsel.co.id';
                $newUser->setPassword('dummy');
                $newUser->generateAuthKey();
                $newUser->save();
                $model->user_id = strval($newUser->id);

//                var_dump($newUser, $model->errors); exit();
            } else {
                $theUser = $theUsers->one();
                $model->user_id = strval($theUser->id);
                $model->validate();
//                var_dump($model, $model->errors); exit();
            }

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create user assignment data with User ID ".$model->user_id);

                Yii::$app->session->setFlash('success', "User assignment data successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "User assignment data was not created.");
            }


            return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
        }

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
//        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AuthAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $item_name
     * @param string $user_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($item_name, $user_id)
    {
        $model = $this->findModel($item_name, $user_id);

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            // search existing user
            $theUsers = User::find()->where(['username' => $model->user_id]);
            if ($theUsers->count() <= 0) {
                // user does not exist in User table
                $newUser = new User();
                $newUser->nik = '';
                $newUser->username = $model->user_id;
                $newUser->id = $model->user_id;
                $newUser->email = $model->user_id.'@telkomsel.co.id';
                $newUser->setPassword('dummy');
                $newUser->generateAuthKey();
                $newUser->save();
                $model->user_id = strval($newUser->id);

                //var_dump($newUser, $model->errors); exit();
            } else {

                $theUser = $theUsers->one();
                $model->user_id = strval($theUser->id);
                $model->validate();
//                var_dump($theUser, $model->errors); exit();
            }

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update user assignment data with User ID ".$model->user_id);

                Yii::$app->session->setFlash('success', "User assignment data successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "User assignment data was not updated.");
            }

            return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
        } else {
            $theUser = User::find()->where(['id' => $model->user_id])->one();
            $model->user_id = $theUser->username;
        }

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'item_name' => $model->item_name, 'user_id' => $model->user_id]);
//        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AuthAssignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $item_name
     * @param string $user_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($item_name, $user_id)
    {

        if ($this->findModel($item_name, $user_id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete user assignment data for username ".$user_id);

            Yii::$app->session->setFlash('success', "User assignment data successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "User assignment data was not deleted.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $item_name
     * @param string $user_id
     * @return AuthAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($item_name, $user_id)
    {
        if (($model = AuthAssignment::findOne(['item_name' => $item_name, 'user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
