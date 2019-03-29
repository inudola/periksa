<?php

namespace reward\controllers;

use reward\models\RewardLog;
use Yii;
use reward\models\Category;
use reward\models\CategorySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->icon = UploadedFile::getInstance($model, 'icon');
            $model->icon->saveAs(Yii::getAlias('@reward/web/img/category_reward/') . $model->icon->baseName . '.' . $model->icon->extension);
            $model->icon = 'img/category_reward/' . $model->icon->baseName . '.' . $model->icon->extension;

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create a new category with ID " . $model->id);
                Yii::$app->session->setFlash('success', "Your category successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your category was not saved.");
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $file = $model->icon;
        if (isset($_POST['Category'])) {
            $model->attributes = $_POST['Category'];
            $model->icon = $file;

            if (isset($_FILES['Category'])) {
                $model->icon = UploadedFile::getInstance($model, 'icon');
                if (isset($model->icon)) {
                    $model->icon->saveAs(Yii::getAlias('@reward/web/img/category_reward/') . $model->icon->baseName . '.' . $model->icon->extension);
                    $model->icon = 'img/category_reward/' . $model->icon->baseName . '.' . $model->icon->extension;
                } else {
                    $model->icon = $file;
                }
            } else {
                $model->icon = $file;
            }

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Updated category with ID " . $model->id);
                Yii::$app->session->setFlash('success', "Your category successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your category was not saved.");
            }


            $this->redirect(array('view', 'id' => $model->id));

        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Category with ID " . $id);
            Yii::$app->session->setFlash('success', "Your category successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your category was not deleted.");
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
