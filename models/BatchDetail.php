<?php

namespace reward\models;

use DateTime;
use reward\components\Helpers;
use Yii;

/**
 * This is the model class for table "batch_detail".
 *
 * @property int $id
 * @property string $simulation_id
 * @property int $bulan
 * @property int $tahun
 * @property string $element
 * @property double $amount
 * @property string $batch_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BatchEntry $batch
 * @property Simulation $simulation
 * @property string description
 * @property  nik
 */
class BatchDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $total;

    const GAJI_DASAR = 'GAJI DASAR';
    const TBH = 'TBH';
    const REKOMPOSISI = 'REKOMPOSISI';
    const TUNJAB = 'TUNJANGAN JABATAN';

    const UANG_SAKU = 'UANG SAKU';

    public static function tableName()
    {
        return 'batch_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['simulation_id', 'bulan', 'tahun', 'batch_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at', 'nik'], 'safe'],
            [['element', 'description'], 'string', 'max' => 64],
            [['batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => BatchEntry::className(), 'targetAttribute' => ['batch_id' => 'id']],
            [['simulation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Simulation::className(), 'targetAttribute' => ['simulation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'simulation_id' => 'Simulation ID',
            'bulan' => 'Bulan',
            'tahun' => 'Tahun',
            'element' => 'Element',
            'description' => 'Tipe',
            'amount' => 'Amount',
            'nik' => 'Nik',
            'batch_id' => 'Batch ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'total' => 'Total'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatch()
    {
        return $this->hasOne(BatchEntry::className(), ['id' => 'batch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimulation()
    {
        return $this->hasOne(Simulation::className(), ['id' => 'simulation_id']);
    }


    public static function getGadas()
    {
        $simId = Yii::$app->session->get('sessionSimId');
        $bulan = Yii::$app->session->get('sessionBulan');
        $tahun = Yii::$app->session->get('sessionTahun');
        $batch = Yii::$app->session->get('sessionBatch');

        $element = ['UANG SAKU', 'GAJI DASAR'];

        $model = BatchDetail::find()->select('amount')
            ->where(['simulation_id' => $simId])
            ->andWhere(['bulan' => $bulan])
            ->andWhere(['tahun' => $tahun])
            ->andWhere(['batch_id' => $batch])
            ->andWhere(['IN', 'element', $element])
            ->one();

        return $model->amount;
    }

    public static function getTbh()
    {
        $simId = Yii::$app->session->get('sessionSimId');
        $bulan = Yii::$app->session->get('sessionBulan');
        $tahun = Yii::$app->session->get('sessionTahun');
        $batch = Yii::$app->session->get('sessionBatch');


        $model = BatchDetail::find()->select('amount')
            ->where(['simulation_id' => $simId])
            ->andWhere(['bulan' => $bulan])
            ->andWhere(['tahun' => $tahun])
            ->andWhere(['batch_id' => $batch])
            ->andWhere(['element' => BatchDetail::TBH])
            ->one();

        return $model->amount;
    }

    public static function getRekomposisi()
    {
        $simId = Yii::$app->session->get('sessionSimId');
        $bulan = Yii::$app->session->get('sessionBulan');
        $tahun = Yii::$app->session->get('sessionTahun');
        $batch = Yii::$app->session->get('sessionBatch');


        $model = BatchDetail::find()->select('amount')
            ->where(['simulation_id' => $simId])
            ->andWhere(['bulan' => $bulan])
            ->andWhere(['tahun' => $tahun])
            ->andWhere(['batch_id' => $batch])
            ->andWhere(['element' => BatchDetail::REKOMPOSISI])
            ->one();

        return $model->amount;
    }

    public static function getTunjab()
    {
        $simId = Yii::$app->session->get('sessionSimId');
        $bulan = Yii::$app->session->get('sessionBulan');
        $tahun = Yii::$app->session->get('sessionTahun');
        $batch = Yii::$app->session->get('sessionBatch');


        $model = BatchDetail::find()->select('amount')
            ->where(['simulation_id' => $simId])
            ->andWhere(['bulan' => $bulan])
            ->andWhere(['tahun' => $tahun])
            ->andWhere(['batch_id' => $batch])
            ->andWhere(['element' => BatchDetail::TUNJAB])
            ->one();

        return $model->amount;
    }


    public function setElement($simId, $bulan, $tahun, $element, $amount)
    {

        $simulation = new SimulationDetail();
        $simulation->simulation_id = $simId;
        $simulation->bulan = $bulan;
        $simulation->tahun = $tahun;
        $simulation->element = $element;
        $simulation->amount = $amount;
        $simulation->save();

    }


    public function setDelBatch($simId, $bulan, $tahun, $batch)
    {

        //get value from setting model
        $indexTHR = Setting::getindexThr();
        $indexTunjCuti = Setting::getindexTunjCuti();
        $iuranKes = Setting::getiuranKes();
        $iuranJHT = Setting::getiuranJHT();
        $iuranJP = Setting::getiuranJP();
        $iuranJKK = Setting::getiuranJKK();
        $iuranJKM = Setting::getiuranJKM();
        $tax = Setting::getTax();
        $indexCutiBesar = Setting::getindexCutiBesar();

        //value PMK Structure
        $indexPMK5 = Setting::getindexPMK5();
        $indexPMK10 = Setting::getindexPMK10();
        $indexPMK15 = Setting::getindexPMK15();
        $indexPMK20 = Setting::getindexPMK20();
        $indexPMK25 = Setting::getindexPMK25();
        $indexPMK30 = Setting::getindexPMK30();

        //get projection period
        $getSimulation = Simulation::find()->where(['id' => $simId])->one();
        $tahun = date("Y", strtotime($getSimulation->start_date));
        $startMonth = date('Y-m-d', strtotime($tahun . '-' . $bulan));
        $endMonth = date("n", strtotime($getSimulation->end_date));


        $x = Yii::$app->db->createCommand("
            DELETE FROM simulation_detail 
            WHERE simulation_id = '$simId' 
            AND bulan BETWEEN '$bulan' AND '$endMonth'
            AND tahun = '$tahun'
            ")->execute();


        //$nikYangDimaksud = ['T118001', '87039', '71206', '86068', '86065', '88003', '214046', '79205'];

        // ambil semua nik unik
        $niks = Employee::find()->select('nik')
            ->where(['status' => Employee::ACTIVE])
            ->andWhere(['not', ['salary' => NULL]])
            //->andFilterWhere(['nik' => $nikYangDimaksud])
            ->andFilterWhere(['not', ['tunjangan' => NULL]])
            ->andFilterWhere(['not', ['tunjangan_rekomposisi' => NULL]])
            ->distinct()
            ->all();

        // ambil semua career path employee
        $data = [];

        $dates = Helpers::getMonthIterator($startMonth, $getSimulation->end_date);

        // ambil tabel gaji
        $gajiTable = MstGaji::find()->asArray()->all();


        //get the last array of projection period
        $element = [];
        $start = current(array_keys($data));
        $end = end(array_keys($data));
        $theFirstYear = intval(substr($start, 0, 4));
        $theLastYear = intval(substr($end, 0, 4));
        $theFirstMonth = 12;
        $theFirstMonths = intval(substr($start, 4, 2));
        $theMonths = substr($end, 4, 2);
        $theLastMonth = intval(substr($end, 4, 2));

        //get nilai yang akan dihapus
        $batchEntry = [];
        $batchEntry['GAJI DASAR'] = $this->gadas;
        $batchEntry['TBH'] = $this->tbh;
        $batchEntry['REKOMPOSISI'] = $this->rekomposisi;
        $batchEntry['TOTAL'] = $batchEntry['GAJI DASAR'] + $batchEntry['TBH'] + $batchEntry['REKOMPOSISI'];

        //get previous values from batch detail
        $elm = ['UANG SAKU', 'GAJI DASAR'];
        $batchDetail = [];

        foreach ($niks as $theNik) {
            // get employee
            $theEmployee = Employee::find()
                ->where(['status' => Employee::ACTIVE])
                ->andWhere(['nik' => $theNik->nik])
                ->orderBy(['start_date_assignment' => SORT_DESC])
                ->one();

            $careerPath = $theEmployee->getCareerPath($theEmployee->start_date_assignment, $getSimulation->end_date);

            $maxGaji = $theEmployee->salary;
            $maxTbh = $theEmployee->tunjangan;
            $maxRekomposisi = $theEmployee->tunjangan_rekomposisi;
            $maxTunjab = $theEmployee->tunjangan_jabatan;

            //get masa kerja
            $tahunMasuk = date("Y-m", strtotime($theEmployee->tanggal_masuk));

            $prevGajiDasar = $maxGaji;
            $prevTbh = $maxTbh;
            $prevRekomposisi = $maxRekomposisi;
            $prevTunjab = $maxTunjab;


            /**
             * @var $date \DateTime
             */
            foreach ($dates as $date) {

                $dateFmt = $date->format('Ym');

                $prevDate = clone $date;
                $prevDate = $prevDate->modify('-1 month');
                $prevDateFmt = $prevDate->format('Ym');

                //get previos values from batch_detail
                $theYear = intval(substr($dateFmt, 0, 4));
                $theMonth = intval(substr($dateFmt, 4, 2));

                $gadasPrev = BatchDetail::find()
                    ->select('amount')
                    ->where(['simulation_id' => $simId])
                    ->andWhere(['bulan' => $theMonth])
                    ->andWhere(['tahun' => $theYear])
                    ->andWhere(['IN', 'element', $elm])
                    ->sum('amount');

                $tbhPrev = BatchDetail::find()
                    ->select('amount')
                    ->where(['simulation_id' => $simId])
                    ->andWhere(['bulan' => $theMonth])
                    ->andWhere(['tahun' => $theYear])
                    ->andWhere(['element' => BatchDetail::TBH])
                    ->sum('amount');

                $rekomposisiPrev = BatchDetail::find()
                    ->select('amount')
                    ->where(['simulation_id' => $simId])
                    ->andWhere(['bulan' => $theMonth])
                    ->andWhere(['tahun' => $theYear])
                    ->andWhere(['element' => BatchDetail::REKOMPOSISI])
                    ->sum('amount');


                $data[$dateFmt]['selisih_gaji_dasar'] += 0;
                $data[$dateFmt]['selisih_tbh'] += 0;
                $data[$dateFmt]['selisih_rekomposisi'] += 0;
                $data[$dateFmt]['selisih_tunjangan_jabatan'] += 0;

                $data[$dateFmt]['jumlah_newbi'] += 0; // catat jumlah employee yg naik BI nya

                //hitung masa kerja karyawan
                $currentYear = $date->format('Y-m');
                $diff = (new DateTime($tahunMasuk))->diff(new DateTime($currentYear));
                $masaKerja = $diff->m + $diff->y * 12;


                // ambil gaji
                $oldBi = 'N/A';
                if (empty($careerPath['path'])) {
                    // bi atau bp nya null --> contract
                    $gaji = $theEmployee->salary;
                    $tbh = $theEmployee->tunjangan;
                    $rekomposisi = $theEmployee->tunjangan_rekomposisi;
                    $tunjab = $theEmployee->tunjangan_jabatan;
                } elseif ($theEmployee->bi == $careerPath['path'][$dateFmt]['bi']) {
                    $gaji = $theEmployee->salary;
                    $tbh = $theEmployee->tunjangan;
                    $rekomposisi = $theEmployee->tunjangan_rekomposisi;
                    $tunjab = $theEmployee->tunjangan_jabatan;

                } else {
                    $oldBi = $careerPath['path'][$prevDateFmt]['bi'];

                    if ($oldBi == $theEmployee->bi) {
                        $oldGaji = $theEmployee->salary;
                        $oldTbh = $theEmployee->tunjangan;
                        $oldRekomposisi = $theEmployee->tunjangan_rekomposisi;
                        $oldTunjab = $theEmployee->tunjangan_jabatan;
                    } else {
                        //                      $theOldGaji = MstGaji::find()->cache(3600)->where(['bi' => $oldBi])->one();
                        $key = array_search($oldBi, array_column($gajiTable, 'bi'));
                        $theOldGaji = $gajiTable[$key];
                        $oldGaji = $theOldGaji['gaji_dasar'];
                        $oldTbh = $theOldGaji['tunjangan_biaya_hidup'];
                        $oldRekomposisi = $theOldGaji['tunjangan_rekomposisi'];

                        if ($theEmployee->structural) {
                            $oldTunjab = $theOldGaji['tunjangan_jabatan_struktural'];
                        } else {
                            $oldTunjab = $theOldGaji['tunjangan_jabatan_functional'];
                        }

                    }

//                    $theGaji = MstGaji::find()->cache(3600)->where(['bi' => $careerPath['path'][$dateFmt]['bi']])->one();

                    $key = array_search($careerPath['path'][$dateFmt]['bi'], array_column($gajiTable, 'bi'));
                    $theGaji = $gajiTable[$key];

                    $gaji = $theGaji['gaji_dasar'];
                    $tbh = $theGaji['tunjangan_biaya_hidup'];
                    $rekomposisi = $theGaji['tunjangan_rekomposisi'];
                    if ($theEmployee->structural) {
                        $tunjab = $theGaji['tunjangan_jabatan_struktural'];
                    } else {
                        $tunjab = $theGaji['tunjangan_jabatan_functional'];
                    }

                }


                if ($careerPath['path'][$dateFmt]['is_naik_bi']) {

                    if ($prevGajiDasar > $gaji) {
                        echo "\n===========\nnew Gaji Dasar is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevGajiDasar) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($gaji) . " (from mst_gaji table) \n";
                        $gaji = $prevGajiDasar;
                    }

                    if ($maxGaji > $gaji) {
                        $gaji = $maxGaji;
                    }

                    if ($prevTbh > $tbh) {
                        echo "\n===========\nnew TBH is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTbh) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($tbh) . " (from mst_gaji table) \n";
                        $tbh = $prevTbh;
                    }

                    if ($maxTbh > $tbh) {
                        $tbh = $maxTbh;
                    }

                    if ($prevRekomposisi > $rekomposisi) {
                        echo "\n===========\nnew REkomposisi is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevRekomposisi) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($rekomposisi) . " (from mst_gaji table) \n";
                        $rekomposisi = $prevRekomposisi;
                    }

                    if ($maxRekomposisi > $rekomposisi) {
                        $rekomposisi = $maxRekomposisi;
                    }

                    if ($prevTunjab > $tunjab) {
                        echo "\n===========\nnew Tunjangan Jabatan is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTunjab) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($tunjab) . " (from mst_gaji table) \n";
                        $tunjab = $prevTunjab;
                    }

                    if ($maxTunjab > $tunjab) {
                        $tunjab = $maxTunjab;
                    }

                    $data[$dateFmt]['selisih_gaji_dasar'] += $gaji - $prevGajiDasar;
                    $data[$dateFmt]['selisih_tbh'] += $tbh - $prevTbh;
                    $data[$dateFmt]['selisih_rekomposisi'] += $rekomposisi - $prevRekomposisi;
                    $data[$dateFmt]['selisih_tunjangan_jabatan'] += $tunjab - $prevTunjab;

                    $data[$dateFmt]['jumlah_newbi'] += 1; // catat jumlah employee yg naik BI nya
                }


                if ($maxGaji > $gaji) {
                    $gaji = $maxGaji;
                }
                if ($maxTbh > $tbh) {
                    $tbh = $maxTbh;
                }
                if ($maxRekomposisi > $rekomposisi) {
                    $rekomposisi = $maxRekomposisi;
                }
                if ($maxTunjab > $tunjab) {
                    $tunjab = $maxTunjab;
                }

                $maxGaji = $gaji;
                $maxTbh = $tbh;
                $maxRekomposisi = $rekomposisi;
                $maxTunjab = $tunjab;

                $prevGajiDasar = $gaji;
                $prevTbh = $tbh;
                $prevRekomposisi = $rekomposisi;
                $prevTunjab = $tunjab;

                $data[$dateFmt]['GAJI DASAR'] += $gaji;
                $data[$dateFmt]['TBH'] += $tbh;
                $data[$dateFmt]['REKOMPOSISI'] += $rekomposisi;
                $data[$dateFmt]['TUNJANGAN JABATAN'] += $tunjab;

                if (!empty($batch)) {
                    $batchDetail[$dateFmt]['PREV GAJI DASAR'] = floatval($gadasPrev);
                    $batchDetail[$dateFmt]['PREV TBH'] = floatval($tbhPrev);
                    $batchDetail[$dateFmt]['PREV REKOMPOSISI'] = floatval($rekomposisiPrev);
                    $batchDetail[$dateFmt]['PREV TOTAL'] = $batchDetail[$dateFmt]['PREV GAJI DASAR'] + $batchDetail[$dateFmt]['PREV TBH'] + $batchDetail[$dateFmt]['PREV REKOMPOSISI'];
                }

                if ($getSimulation->perc_inc_gadas > 0 || $getSimulation->perc_inc_tbh > 0 || $getSimulation->perc_inc_rekomposisi > 0) {
                    $data[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($getSimulation->perc_inc_gadas) ? 0 : $data[$dateFmt]['GAJI DASAR'] * $getSimulation->perc_inc_gadas / 100);
                    $data[$dateFmt]['KENAIKAN TBH'] =
                        (empty($getSimulation->perc_inc_tbh) ? 0 : $data[$dateFmt]['TBH'] * $getSimulation->perc_inc_tbh / 100);
                    $data[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($getSimulation->perc_inc_rekomposisi) ? 0 : $data[$dateFmt]['REKOMPOSISI'] * $getSimulation->perc_inc_rekomposisi / 100);
                    $data[$dateFmt]['TOTAL KENAIKAN'] = $data[$dateFmt]['KENAIKAN GADAS'] + $data[$dateFmt]['KENAIKAN TBH'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];
                }

                $data[$dateFmt]['TOTAL GAJI DASAR'] = $data[$dateFmt]['GAJI DASAR'] + $batchDetail[$dateFmt]['PREV GAJI DASAR'] + $data[$dateFmt]['KENAIKAN GADAS'] - $batchEntry['GAJI DASAR'];
                $data[$dateFmt]['TOTAL TBH'] = $data[$dateFmt]['TBH'] + $batchDetail[$dateFmt]['PREV TBH'] + $data[$dateFmt]['KENAIKAN TBH'] - $batchEntry['TBH'];
                $data[$dateFmt]['TOTAL REKOMPOSISI'] = $data[$dateFmt]['REKOMPOSISI'] + $batchDetail[$dateFmt]['PREV REKOMPOSISI'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'] - $batchEntry['REKOMPOSISI'];

                $data[$dateFmt]['TOTAL'] = ($data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['TBH'] + $data[$dateFmt]['REKOMPOSISI']) +
                    (empty($batchDetail[$dateFmt]['PREV TOTAL']) ? 0 : $batchDetail[$dateFmt]['PREV TOTAL']) -
                    (empty($batchEntry['TOTAL']) ? 0 : $batchEntry['TOTAL']) +
                    (empty($data[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $data[$dateFmt]['TOTAL KENAIKAN']);

                $data[$dateFmt]['BPJS KESEHATAN'] = $iuranKes * ($data[$dateFmt]['TOTAL']);
                $data[$dateFmt]['BPJS KETENEGAKERJAAN'] = ($iuranJHT * $data[$dateFmt]['TOTAL']) +
                    ($iuranJP * $data[$dateFmt]['TOTAL']) +
                    ($iuranJKK * $data[$dateFmt]['TOTAL']) +
                    ($iuranJKM * $data[$dateFmt]['TOTAL']);
                $data[$dateFmt]['EMPLOYEE INCOME TAX'] = $tax * $data[$dateFmt]['TOTAL'];

                //PMK => KELIPATAN 5 TAHUN S/D 30 TAHUN
                if ($theEmployee->employee_category == 'PERMANENT') {
                    if ($masaKerja == 60) {
                        $data[$dateFmt]['PMK'] = $indexPMK5 * $data[$dateFmt]['TOTAL'];
                    } else if ($masaKerja == 120) {
                        $data[$dateFmt]['PMK'] = $indexPMK10 * $data[$dateFmt]['TOTAL'];
                    } else if ($masaKerja == 180) {
                        $data[$dateFmt]['PMK'] = $indexPMK15 * $data[$dateFmt]['TOTAL'];
                    } else if ($masaKerja == 240) {
                        $data[$dateFmt]['PMK'] = $indexPMK20 * $data[$dateFmt]['TOTAL'];
                    } else if ($masaKerja == 300) {
                        $data[$dateFmt]['PMK'] = $indexPMK25 * $data[$dateFmt]['TOTAL'];
                    } else if ($masaKerja == 360) {
                        $data[$dateFmt]['PMK'] = $indexPMK30 * $data[$dateFmt]['TOTAL'];
                    }
//                else {
//                    echo "\n===========\n[$currentYear] \n";
//                    echo "nik : " . $theEmployee->nik . " ngga punya PMK\n";
//                    echo "masa kerja : " . $masaKerja . " bulan\n\n";
//                    //$data[$dateFmt]['PMK'] = 0;
//                }
                }

                //CUTI BESAR >= 6 TAHUN
                if ($theEmployee->employee_category == 'PERMANENT') {
                    if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                        $data[$dateFmt]['CUTI BESAR'] = $indexCutiBesar * $data[$dateFmt]['TOTAL'];
                    }
//                else {
//                    echo "\n===========\n[$currentYear] \n";
//                    echo "nik : " . $theEmployee->nik . " ngga punya CUTI BESAR\n";
//                    echo "masa kerja : " . $masaKerja . " bulan\n\n";
//                    //$data[$dateFmt]['CUTI BESAR'] = 0;
//                }
                }
            }

            $start = current(array_keys($data));
            $end = end(array_keys($data));
            $theFirstYear = intval(substr($start, 0, 4));
            $theLastYear = intval(substr($end, 0, 4));
            $theFirstMonth = 12;
            $theMonths = substr($end, 4, 2);
            $theFirstMonths = intval(substr($start, 4, 2));
            $theLastMonth = intval(substr($end, 4, 2));


            for ($i = $theFirstYear; $i <= $theLastYear; $i++) {

                if ($i < $theLastYear) {
                    //echo "pakai". $theFirstMonth."\n";
                    $element[$i . $theFirstMonth]['LAST TOTAL'] = $data[$i . $theFirstMonth]['TOTAL'];
                    $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;

                } else {
                    //echo "pakai". $theLastMonth."\n";
                    $element[$i . $theMonths]['LAST TOTAL'] = $data[$i . $theMonths]['TOTAL'];
                    $element[$i . $theMonths]['THR'] = (($indexTHR * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);

                }
            }
        }


        for ($i = $theFirstYear; $i <= $theLastYear; $i++) {
            if ($i < $theLastYear) {
                for ($y = $theFirstMonths; $y <= $theFirstMonth; $y++) {
                    $this->setElement($simId, $y, $theFirstYear, 'THR', $element[$i . $theFirstMonth]['THR']);
                    $this->setElement($simId, $y, $theFirstYear, 'CUTI TAHUNAN', $element[$i . $theFirstMonth]['CUTI TAHUNAN']);

                }
            } else {
                for ($y = $theFirstMonths; $y <= $theLastMonth; $y++) {
                    $this->setElement($simId, $y, $theLastYear, 'THR', $element[$i . $theMonths]['THR']);
                    $this->setElement($simId, $y, $theLastYear, 'CUTI TAHUNAN', $element[$i . $theMonths]['CUTI TAHUNAN']);

                }
            }
        }


        foreach ($data as $i => $rows) {

            $theYear = substr($i, 0, 4);
            $theMonth = substr($i, 4, 2);


            $this->setElement($simId, $theMonth, $theYear, 'GAJI DASAR', $data[$i]['TOTAL GAJI DASAR']);
            $this->setElement($simId, $theMonth, $theYear, 'TBH', $data[$i]['TOTAL TBH']);
            $this->setElement($simId, $theMonth, $theYear, 'TUNJANGAN REKOMPOSISI', $data[$i]['TOTAL REKOMPOSISI']);
            $this->setElement($simId, $theMonth, $theYear, 'TUNJANGAN JABATAN', $data[$i]['TUNJANGAN JABATAN']);
            $this->setElement($simId, $theMonth, $theYear, 'BPJS KESEHATAN', $data[$i]['BPJS KESEHATAN']);
            $this->setElement($simId, $theMonth, $theYear, 'BPJS KETENEGAKERJAAN', $data[$i]['BPJS KETENEGAKERJAAN']);
            $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEE INCOME TAX', $data[$i]['EMPLOYEE INCOME TAX']);
            $this->setElement($simId, $theMonth, $theYear, 'PENGHARGAAN MASA KERJA', $data[$i]['PMK']);
            $this->setElement($simId, $theMonth, $theYear, 'CUTI BESAR', $data[$i]['CUTI BESAR']);

        }

        Yii::$app->session->remove('sessionSimId');
        Yii::$app->session->remove('sessionBulan');
        Yii::$app->session->remove('sessionTahun');
        Yii::$app->session->remove('sessionBatch');

        //var_dump($data, $batchEntry);

        return $data;
    }
}
