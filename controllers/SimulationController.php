<?php

namespace reward\controllers;

use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use reward\models\MstElement;
use reward\models\RewardLog;
use reward\models\Setting;
use reward\models\Simulation;
use reward\models\SimulationDetail;
use reward\models\SimulationDetailSearch;
use reward\models\SimulationDetailTestSearch;
use reward\models\SimulationTest;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SimulController implements the CRUD actions for Simulation model.
 */
class SimulationController extends Controller
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
     * Lists all Simulation models.
     * @return mixed
     */
    public function actionIndex()
    {
        //get last projection
        $lastProjection = SimulationDetail::find()->orderBy(['simulation_id' => SORT_DESC])->one();
        $lastSimulation = Simulation::find()->orderBy(['id' => SORT_DESC])->one();


        /*Dropdownlist history projection*/
        $simulation = Simulation::getListSimulations();

        $searchModel = new SimulationDetailSearch();
        $dataProvider = $searchModel->search11(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['id' => SORT_ASC]];
        $dataProvider->query->where(['NOT', ['n_group' => null]]);
        $dataProvider->query->andwhere(['simulation_id' => $lastSimulation->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'list' => $simulation,
            'query' => $lastProjection,
            'query1' => $lastSimulation,
        ]);
    }

    /**
     * Displays a single Simulation model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        return $this->render('//simulation-detail/view', [
            'model' => $this->findModel($id),
        ]);

    }

    /**
     * Creates a new Simulation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Simulation();


        if (Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());

            $cmd = Yii::getAlias('@webroot') . '/../../yii simul/create "' . $model->start_date . '" "' . $model->end_date . '" "' . $model->perc_inc_gadas . '" "' . $model->perc_inc_tbh . '" "' . $model->perc_inc_rekomposisi . '" "' . $model->description . '"';
            $output = shell_exec($cmd);

            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Create Simulation from ".$model->start_date. " to ".$model->end_date);
            Yii::$app->session->setFlash('success', "Your simulation successfully created.");

            return $this->redirect(['index']);
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Simulation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            if ($model->save()) {
                //logging data
                RewardLog::saveLog(Yii::$app->user->identity->username, "Update Simulation with ID ".$model->id);
                Yii::$app->session->setFlash('success', "Your simulation successfully updated.");
            } else {
                Yii::$app->session->setFlash('error', "Your simulation was not saved.");
            }
            //return $this->redirect(['index']);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('//simulation-detail/update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Simulation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        if ($this->findModel($id)->delete()) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Simulation with ID ".$id);
            Yii::$app->session->setFlash('success', "Your simulation successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your simulation was not deleted.");
        }

        return $this->redirect(['index']);
    }

    public function actionDel($id, $mode)
    {

        $delete = Yii::$app->db->createCommand("
            DELETE FROM simulation_detail 
            WHERE simulation_id = '$id' 
            AND keterangan = '$mode' 
         ")->execute();

        if ($delete) {
            //logging data
            RewardLog::saveLog(Yii::$app->user->identity->username, "Delete Simulation Detail with Simulation ID ".$id. "and Keterangan ".$mode);
            Yii::$app->session->setFlash('success', "Your simulation detail successfully deleted.");
        } else {
            Yii::$app->session->setFlash('error', "Your simulation detail was not deleted.");
        }
        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
    }

    /**
     * Finds the Simulation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Simulation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SimulationDetail::findOne($id)) !== null) {
            return $model;
        }


        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel1($id)
    {

        if (($model = Simulation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*Get data simultion based on filter simulation id*/
    public function actionGetDataTabel($id, $mode)
    {
        $lastProjection = SimulationDetail::find()->where(['simulation_id' => $id])->one();
        $lastSimulation = Simulation::find()->orderBy(['id' => $id])->one();

        $findAlt = SimulationDetail::find()
            ->select('keterangan')
            ->where(['simulation_id' => $id])
            ->andWhere(['not', ['keterangan' => 'ORIGINAL BUDGET']])
            ->asArray()
            ->all();


        $searchModel = new SimulationDetailSearch();

        /*====================get data simulation start=========================*/
        $dataProvider = $searchModel->search11(Yii::$app->request->queryParams);
        $dataProvider->query->where(['simulation_id' => $id])->andWhere(['keterangan' => 'ORIGINAL BUDGET'])->andWhere(['NOT', ['n_group' => null]]);
        /*====================get data simulation end=========================*/

        /*====================get data alternatif start=========================*/
        $dataProviderAlt = $searchModel->search11(Yii::$app->request->queryParams);
        $dataProviderAlt->sort = ['defaultOrder' => ['bulan' => SORT_ASC, 'tahun' => SORT_ASC]];
        $dataProviderAlt->query->where(['simulation_id' => $id])->andWhere(['IN', 'keterangan' , [$mode, 'ORIGINAL BUDGET']])->andWhere(['NOT', ['n_group' => null]]);
        /*====================get data alternatif end=========================*/


        return $this->renderAjax('_dataSimulation', [
            'dataProvider' => $dataProvider,
            'dataProviderAlt' => $dataProviderAlt,
            'query' => $lastProjection,
            'query1' => $lastSimulation,
            'mode' => $mode,
            'findAlt' => $findAlt
        ]);
    }


    public function actionExport($id, $format = 'excel')
    {

        $theSimulation = $this->findModel1($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'PROJECTION');
        $centerAndBold = [
            'font' => [
                'bold' => true,
                'size' => 18,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1')->applyFromArray($centerAndBold);

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', 'PERIODE : ' . date("d-M-Y", strtotime($theSimulation->start_date)) . ' S/D ' . date("d-M-Y", strtotime($theSimulation->end_date)));
        $sheet->getStyle('A2')->applyFromArray($centerAndBold);

        $teksTanggal = 'Description : ' . $theSimulation->description;

        $htmlHelper = new Html();
        $sheet->setCellValue('A4', $htmlHelper->toRichTextObject($teksTanggal));
        $sheet->getStyle('A4')->applyFromArray([

            'font' => [
                'size' => 16
            ]

        ]);

        // No	Bulan	Element Detail	Amount	Total
        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'Bulan');
        $sheet->setCellValue('C6', 'Element Detail');
        //$sheet->mergeCells('D6:E6');
        $sheet->setCellValue('D6', 'Amount');
        //$sheet->mergeCells('F6:G6');
        $sheet->setCellValue('E6', 'Total');

        $sheet->getStyle('A6:E6')->applyFromArray([

            'font' => [
                'size' => 12,
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],

        ]);

        $startRow = 7;

        $simulationList = SimulationDetail::find()
            ->select([
                '{{simulation_detail}}.*',
                'SUM({{simulation_detail}}.amount) AS sumProj',
            ])
            ->where(['simulation_id' => $id])
            ->andWhere(['NOT', ['n_group' => null]])
            ->groupBy(['bulan', 'tahun'])
            ->all();


        $allBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ]
        ];
        $outlineBorder = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ]
        ];

        $counter = 1;
        foreach ($simulationList as $simulation) {
            $beginRow = $startRow;
            $sheet->setCellValue('A' . $startRow, $counter);
            $sheet->setCellValue('B' . $startRow, $simulation->bulan);
            $sheet->setCellValue('C' . $startRow, '');
            $sheet->setCellValue('D' . $startRow, '');
            $sheet->setCellValue('E' . $startRow, floatval($simulation->sumProj));

            $elements = SimulationDetail::find()
                ->select([
                    '{{simulation_detail}}.*', // select all fields
                    'SUM({{simulation_detail}}.amount) AS my_sum' // calculate orders count
                ])
                //->joinWith('mst_nature')
                ->where(['simulation_id' => $id])
                ->andWhere(['NOT', ['n_group' => null]])
                ->andWhere(['bulan' => $simulation->bulan, 'tahun' => $simulation->tahun])
                ->groupBy('n_group')
                ->all();

            foreach ($elements as $element) {

                // Element
                $startRow++;
                $sheet->setCellValue('C' . $startRow, $element->mstNature->nature_name);
                $sheet->setCellValue('D' . $startRow, floatval($element->my_sum));
            }


            $endRow = $startRow;
            // ABCDE
            $sheet->getStyle('A' . $beginRow . ':A' . $endRow)->applyFromArray($outlineBorder);
            $sheet->getStyle('B' . $beginRow . ':B' . $endRow)->applyFromArray($outlineBorder);
            $sheet->getStyle('C' . $beginRow . ':C' . $endRow)->applyFromArray($outlineBorder);
            $sheet->getStyle('D' . $beginRow . ':D' . $endRow)->applyFromArray($outlineBorder);
            $sheet->getStyle('E' . $beginRow . ':E' . $endRow)->applyFromArray($outlineBorder);
            $sheet->mergeCells('E' . $beginRow . ':E' . $endRow)->getStyle('E' . $beginRow . ':E' . $endRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_JUSTIFY,
                    'vertical' => Alignment::VERTICAL_TOP,
                ]
            ]);

            $startRow++; // next simulation in the list
            $counter++;
        }

        foreach (range('B', 'I') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
                ->setAutoSize(true);
        }


        $startRow += 4;
        $fontSmallItalic = [
            'font' => [
                'italic' => true,
                'size' => 10,
            ],
        ];


        // paper orientation and size
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        // fit one page wide
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);


        switch ($format) {
            case 'excel':
                // Redirect output to a client’s web browser (Xlsx)
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="Projection' . '_' . $id . '_' . date('YmdHis') . '.xlsx"');

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                break;

            case 'pdf':
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment;filename="Projection' . '_' . $id . '_' . date('YmdHis') . '.pdf"');
                $writer = IOFactory::createWriter($spreadsheet, 'Mpdf');
                $writer->setTempDir(Yii::getAlias('@webroot') . '/tmp');
                break;
        }


        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer->save('php://output');
        exit;

    }

    public function actionTest()
    {
        $model = new Simulation();

        if (Yii::$app->request->isPost) {

            $model->load(Yii::$app->request->post());
            $cmd = Yii::getAlias('@webroot') . '/../../yii simul/test-simulation "' . $model->start_date . '" "' . $model->end_date . '" "' . $model->nik . '" "' . $model->perc_inc_gadas . '" "' . $model->perc_inc_tbh . '" "' . $model->perc_inc_rekomposisi . '"';
            $output = shell_exec($cmd);

            var_dump($output);
            exit();
        }


        return $this->render('test', [
            'model' => $model,
        ]);
    }

    public function actionElementTest()
    {
        $model = new Simulation();


        return $this->render('test', [
            'model' => $model,
        ]);
    }

}
