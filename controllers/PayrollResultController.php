<?php

namespace reward\controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use reward\models\PayrollResultOci;
use reward\models\RewardLog;
use reward\models\UploadXlsxForm;
use Yii;
use reward\models\PayrollResult;
use reward\models\PayrollResultSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PayrollResultController implements the CRUD actions for PayrollResult model.
 */
class PayrollResultController extends Controller
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
     * Lists all PayrollResult models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PayrollResultSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['resource' => 'Revex']);

        $uploadModel = new UploadXlsxForm();

        /*Dropdownlist history projection*/
        $list = PayrollResult::getList();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'uploadModel' => $uploadModel,
            'list' => $list,
        ]);
    }

    /**
     * Displays a single PayrollResult model.
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
     * Creates a new PayrollResult model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PayrollResult();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PayrollResult model.
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
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update Batch Entry (Payroll Result) with ID ".$model->id);
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

    /**
     * Deletes an existing PayrollResult model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Payroll Result with ID ".$id);
            Yii::$app->session->setFlash('success', "Your payroll result successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your payroll result was not deleted.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the PayrollResult model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PayrollResult the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PayrollResult::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionImport()
    {
        $model = new UploadXlsxForm();

        if (Yii::$app->request->isPost) {
            $x = Yii::$app->request->Post();

            $model->userFile = UploadedFile::getInstance($model, 'userFile');
            if ($model->upload()) {

                // check if overwrite
                $trans = Yii::$app->db->beginTransaction();
                if (intval($x['UploadXlsxForm']['overwrite']) == 1) {
                    // truncate old data
                    Yii::$app->db->createCommand()->truncateTable('payroll_result')->execute();
                }

                // file is uploaded successfully... read excel file
                $spreadsheet = IOFactory::load('uploads/' . $model->getFinalName());
                $worksheet = $spreadsheet->getActiveSheet();
                $maxRow = $worksheet->getHighestRow();
                $maxColumn = $worksheet->getHighestColumn();
                $maxColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxColumn);


                // read all cell and save it to DB
                $successCount = 0;
                $failCount = 0;
                $rowsError = [];
                for ($row = 2; $row <= $maxRow; ++$row) {

                    $newPayroll = new PayrollResult();
                    $newPayroll->payroll_name = strval($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                    $newPayroll->period_bulan = intval($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue());
                    $newPayroll->period_tahun = intval($worksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue());
                    $newPayroll->element_name = strval($worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue());
                    $newPayroll->curr_amount = floatval($worksheet->getCellByColumnAndRow(6, $row)->getCalculatedValue());
                    $newPayroll->resource = strval($worksheet->getCellByColumnAndRow(7, $row)->getCalculatedValue());

                    if ($newPayroll->save()) {

                        $successCount++;
                    } else {
                        $trans->rollBack();
                        $successCount = 0;
                        $failCount++;
                        $rowsError[] = $row-1;
                        break;
                    }

                }

                if ($trans->isActive) $trans->commit();

                return $this->render('importStatus', [
                    'rowsError' => $rowsError,
                    'successCount' => $successCount,
                    'failCount' => $failCount,
                ]);
            }
        } else {
            return $this->redirect(['index']);
        }
    }


    public function actionAddBatch($bulan, $tahun, $resource)
    {
        $model = new PayrollResult();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Add a new Batch Entry (Payroll Results) with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your batch entry successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your batch entry was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('add', [
            'model' => $model,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'resource' => $resource
        ]);
    }

    /*Get data simultion based on filter simulation id*/
    public function actionGetDataTabel($mode)
    {
        $real = PayrollResult::find()->where(['resource' => $mode])->one();

        $searchModel = new PayrollResultSearch();

        /*====================get data realization start=========================*/
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['resource' => $mode]);
        /*====================get data realization end=========================*/

        $uploadModel = new UploadXlsxForm();

        return $this->renderAjax('_dataTable', [
            'dataProvider' => $dataProvider,
            'query' => $real,
            'mode' => $mode,
            'uploadModel' => $uploadModel
        ]);
    }

}
