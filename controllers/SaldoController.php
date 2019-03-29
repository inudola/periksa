<?php

namespace reward\controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use reward\models\RewardLog;
use reward\models\UploadXlsxForm;
use Yii;
use reward\models\SaldoNki;
use reward\models\SaldoNkiSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SaldoController implements the CRUD actions for SaldoNki model.
 */
class SaldoController extends Controller
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
     * Lists all SaldoNki models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaldoNkiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $uploadModel = new UploadXlsxForm();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'uploadModel' => $uploadModel
        ]);

    }

    /**
     * Displays a single SaldoNki model.
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
     * Creates a new SaldoNki model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SaldoNki();

        if ($model->load(Yii::$app->request->post()) ) {
            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Create saldo nki with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your saldo nki successfully created.");
            } else {
                Yii::$app->session->setFlash('error', "Your saldo nki was not saved.");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
                    Yii::$app->db->createCommand()->truncateTable('saldo_nki')->execute();
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

                    $newSaldo = new SaldoNki();
                    $newSaldo->nik = strval($worksheet->getCellByColumnAndRow(2, $row)->getCalculatedValue());
                    $newSaldo->bi = strval($worksheet->getCellByColumnAndRow(3, $row)->getCalculatedValue());
                    $newSaldo->smt = strval($worksheet->getCellByColumnAndRow(4, $row)->getCalculatedValue());
                    $newSaldo->tahun = strval($worksheet->getCellByColumnAndRow(5, $row)->getCalculatedValue());
                    $newSaldo->score = strval($worksheet->getCellByColumnAndRow(6, $row)->getCalculatedValue());
                    $newSaldo->total = strval($worksheet->getCellByColumnAndRow(7, $row)->getCalculatedValue());
                    if ($newSaldo->save()) {
                        //logging data
                        RewardLog::saveLog(Yii::$app->user->identity->username, "Import payroll result with ID ".$newSaldo->id);
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



    /**
     * Updates an existing SaldoNki model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SaldoNki model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Saldo NKI with ID ".$id);
            Yii::$app->session->setFlash('success', "Your saldo nki successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your saldo nki was not deleted.");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the SaldoNki model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SaldoNki the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SaldoNki::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    
}
