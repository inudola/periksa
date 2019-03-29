<?php

namespace reward\controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use reward\models\BatchDetail;
use reward\models\BatchDetailSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BatchDetailController implements the CRUD actions for BatchDetail model.
 */
class BatchDetailController extends Controller
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
     * Lists all BatchDetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BatchDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BatchDetail model.
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
     * Creates a new BatchDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BatchDetail();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BatchDetail model.
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
     * Deletes an existing BatchDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }



    /**
     * Finds the BatchDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BatchDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BatchDetail::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionViewBatchDetail($simId, $bulan, $tahun, $desc)
    {


        $model = BatchDetail::find()->where(['description' => $desc])->one();

        $searchModel = new BatchDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->sort = ['defaultOrder' => ['band_individu'=>SORT_ASC]];
        $dataProvider->query->where(['simulation_id' => $simId])
            ->andwhere(['bulan' => $bulan])
            ->andwhere(['tahun' => $tahun])
            ->andwhere(['description' => $desc])
            ->andWhere(['not', ['element' => 'JUMLAH NEW BI']]);


        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'model' => $model
        ]);
    }

    public function actionExport($id, $format = 'excel')
    {

        $theBatch = $this->findModel($id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'PENYEBAB KENAIKAN');
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

        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'TIPE : ' . $theBatch->description);
        $sheet->getStyle('A2')->applyFromArray($centerAndBold);


        // No	Tipe	Jumlah Karyawan	 Nik
        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'Tipe');
        $sheet->setCellValue('C6', 'Jumlah Karyawan');
        //$sheet->mergeCells('D6:E6');
        $sheet->setCellValue('D6', 'Nik');

        $sheet->getStyle('A6:D6')->applyFromArray([

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

        $query = BatchDetail::find()->where(['id' => $id])
            ->andwhere(['element' => 'JUMLAH NEW BI'])->all();


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
        foreach ($query as $item) {
            $beginRow = $startRow;
            $sheet->setCellValue('A' . $startRow, $counter);
            $sheet->setCellValue('B' . $startRow, $item->description);
            $sheet->setCellValue('C' . $startRow, intval($item->amount));
            $sheet->setCellValue('D' . $startRow, $item->nik);



            $endRow = $startRow;
            // ABCDE
            $sheet->getStyle('A' . $beginRow . ':A' . $endRow)->applyFromArray($outlineBorder);
            $sheet->getStyle('B' . $beginRow . ':B' . $endRow)->applyFromArray($outlineBorder);
            $sheet->getStyle('C' . $beginRow . ':C' . $endRow)->applyFromArray($outlineBorder);
            $sheet->getStyle('D' . $beginRow . ':D' . $endRow)->applyFromArray($outlineBorder);


            $startRow++; // next simulation in the list
            $counter++;
        }

        foreach (range('B', 'D') as $columnID) {
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
                header('Content-Disposition: attachment;filename="SebabKenaikan' . '_' . $id . '_' . date('YmdHis') . '.xlsx"');

                $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

                break;

            case 'pdf':
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment;filename="SebabKenaikan' . '_' . $id . '_' . date('YmdHis') . '.pdf"');
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

}
