<?php

namespace reward\models;

use DateTime;
use reward\components\Helpers;
use Yii;


/**
 * @property mixed bp
 * @property mixed bi
 * @property mixed jumlah_orang
 * @property mixed type_id
 * @property mixed perc_inc_gadas
 * @property mixed perc_inc_rekomposisi
 * @property mixed perc_inc_rekomposisi
 * @property mixed perc_inc_gadas
 * @property mixed perc_inc_tbh
 * @property mixed perc_inc_rekomposisi
 * @property mixed id
 * @property mixed simulation_id
 * @property mixed bulan
 * @property mixed tahun
 * @property mixed created_by
 * @property mixed bp_tujuan
 */
class BatchEntry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $type_element;
    public $bulan;
    public $percentage;
    public $nilai;
    public $type_filter;


    public static function tableName()
    {
        return 'batch_entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['type_id', 'amount', 'jumlah_orang', 'bi'], 'required'],
            [['type_id'], 'required'],
            [['jumlah_orang', 'amount'], 'default', 'value' => 0],
            [['type_id', 'jumlah_orang', 'perc_inc_gadas', 'perc_inc_tbh', 'perc_inc_rekomposisi'], 'integer'],
            [['created_at', 'updated_at', 'amount', 'jumlah_orang', 'bi', 'created_by'], 'safe'],
            [['bi', 'bp', 'bp_tujuan', 'bulan'], 'string', 'max' => 5],
            [['perc_inc_gadas', 'perc_inc_tbh', 'perc_inc_rekomposisi', 'percentage'], 'integer', 'max' => 99, 'min' => 1],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type',
            'jumlah_orang' => 'Jumlah Orang',
            'bi' => 'Band Individu',
            'bp' => 'Band Position',
            'bp_tujuan' => 'Band Position Tujuan',
            'perc_inc_gadas' => 'Kenaikan Gadas (%)',
            'perc_inc_tbh' => 'Kenaikan TBH (%)',
            'perc_inc_rekomposisi' => 'Kenaikan Rekomposisi (%)',
            'amount' => 'Amount',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMstType()
    {
        return $this->hasOne(MstType::className(), ['id' => 'type_id']);
    }

    public function getMstElement()
    {
        return $this->hasOne(MstElement::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimulationDetails()
    {
        return $this->hasMany(SimulationDetail::className(), ['batch_id' => 'id']);
    }


    public function beforeSave($insert)
    {

        if ($this->mstType->type == 'PROHIRE') {
            if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
                $this->perc_inc_gadas = str_replace(",'", ".", $this->perc_inc_gadas);
                $this->perc_inc_tbh = str_replace(",'", ".", $this->perc_inc_tbh);
                $this->perc_inc_rekomposisi = str_replace(",'", ".", $this->perc_inc_rekomposisi);
            }
        }

        if ($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");
        else
            $this->updated_at = date("Y-m-d H:i:s");


        return parent::beforeSave($insert);
    }


    public function getSalaryStructure()
    {
        $bi = Yii::$app->session->get('sessionBi');

        $model = MstGaji::find()
            ->select('gaji_dasar')
            ->where(['bi' => $bi])
            ->one();

        return $model->gaji_dasar;

    }


    public function getTbhStructure()
    {
        $bi = Yii::$app->session->get('sessionBi');

        $model = MstGaji::find()->select('tunjangan_biaya_hidup')
            ->andWhere(['bi' => $bi])->one();

        return $model->tunjangan_biaya_hidup;
    }


    public function getRekomposisiStructure()
    {

        $bi = Yii::$app->session->get('sessionBi');

        $model = MstGaji::find()->select('tunjangan_rekomposisi')
            ->andWhere(['bi' => $bi])->one();

        return $model->tunjangan_rekomposisi;
    }


    public function getTunjabRotasiStructure()
    {

        $bp = $this->bp_tujuan;

        $model = MstGaji::find()->select('tunjangan_jabatan_struktural, tunjangan_jabatan_functional ')
            ->andWhere(['bi' => $bp])->one();

        $data = empty($model->tunjangan_jabatan_struktural) ? $model->tunjangan_jabatan_functional : $model->tunjangan_jabatan_struktural;

        return $data;
    }

    public static function getTrainee()
    {
        $model = MstGaji::find()->select('gaji_dasar')
            ->andWhere(['bi' => '1.0'])->one();

        return $model->gaji_dasar;
    }


    public static function getNaikTrainee()
    {
        $model = MstGaji::find()->select(['gaji_dasar', 'tunjangan_biaya_hidup', 'tunjangan_rekomposisi'])
            ->where(['bi' => '1.a'])->one();

        return $model;
    }


    public
    function getCareerPath($startDate, $endDate)
    {
        $getSimulation = Simulation::find()->where(['id' => $simId])->one();
        //$endDate = $getSimulation->end_date;
        $tahun = date("Y", strtotime($getSimulation->start_date));
        $startMonth = date('Y-m-d', strtotime($tahun . '-' . $bulan));
        $startMonthPromosi = $bulan + 6;

        $dates = Helpers::getMonthIterator($startMonth, $endDate);

        $careerPath = [];
        $startDateTime = new \DateTime($startDate);
        $startBi = $this->bi;
        $bandBi = 0;
        $bandBp = 0;
        if ($this->bi) $bandBi = intval(substr($this->bi, 0, 1));
        if ($this->bp_tujuan) $bandBp = intval(substr($this->bp_tujuan, 0, 1));


        if (($bandBp > $bandBi) && ($this->bi)) {
            // karyawan evaluasi
            $careerPath['type'] = 'promosi';

            $now = date('Y-m-d');
            $dpeDateTime = new \DateTime($now);
            $dpeDateTime->modify('+5 months');

            $path = [];
            /**
             * @var $date \DateTime
             */
            foreach ($dates as $date) {
                $prevDate = clone $date;
                $prevDate = $prevDate->modify('-1 month');

                $dateFmt = $date->format('Ym');
                $prevDateFmt = $prevDate->format('Ym');


                if (($now) && (intval($dateFmt) > intval($dpeDateTime->format('Ym')))) {
                    // naik BI
                    if ($path) {
                        // check if the target BP is already reached
                        if ($path[$prevDateFmt]['bi'] != $this->bp_tujuan) {
                            // not yet reached
                            if ($path[$prevDateFmt]['bi'] == $this->bi) {
                                // loncat band
                                $nextBi = substr($this->bp_tujuan, 0, 1) . '.1';
                            } else {
                                $nextBi = Helpers::nextBand($path[$prevDateFmt]['bi']);
                            }
                        } else {
                            // stop increasing the bi
                            $nextBi = $path[$prevDateFmt]['bi'];
                        }

                    } elseif ($path[$prevDateFmt]['bi'] == $this->bi) {
                        // loncat band
                        $nextBi = substr($this->bp_tujuan, 0, 1) . '.1';
                    }

                    $path[$dateFmt]['bi'] = $nextBi;
                    $path[$dateFmt]['is_naik_bi'] = true;
                    $path[$dateFmt]['caused_by'] = 'PROMOSI';
                    $path[$dateFmt]['new_bi_band_others'] = 1;

                    // set new DPE date for the next BI increase
                    $dpeDateTime->modify('+6 months');
                    $dpeDateTime->modify('-1 day');
                    $numOfDays = Helpers::getDaysInMonth(intval($dpeDateTime->format('m')), intval($dpeDateTime->format('Y')));
                    $dpeDateTime->setDate(intval($dpeDateTime->format('Y')), intval($dpeDateTime->format('m')), $numOfDays);
//                    var_dump($dpeDateTime->format('Ymd'));
                } else {
                    // masih pakai bi yg lama
                    if ($path) {
                        $path[$dateFmt]['bi'] = $path[$prevDateFmt]['bi'];
                    } else {
                        $path[$dateFmt]['bi'] = $this->bi;
                    }

                    $path[$dateFmt]['is_naik_bi'] = false;

                }
            }

            $careerPath['path'] = $path;
        } else {
            // other type of employee that doesn't have bp and bi
            $careerPath['type'] = 'other';
            $path = []; // just return empty path
            $careerPath['path'] = $path;
        }

        return $careerPath;
    }

    public
    function setElement($simId, $bulan, $tahun, $element, $amount)
    {

        $keterangan = Yii::$app->session->get('keterangan');

        $simulation = new SimulationDetail();
        $simulation->simulation_id = $simId;
        $simulation->bulan = $bulan;
        $simulation->tahun = $tahun;
        $simulation->element = $element;
        $simulation->amount = $amount;
        $simulation->keterangan = $keterangan;
        $simulation->batch_id = $this->id;

        $simulation->save();

    }


    public
    function setAddBatch($simId, $bulan, $tahun)
    {


        //get value from setting model
        $indexTHR = floatval(Setting::getBaseSetting(Setting::INDEX_THR_2));
        $indexTunjCuti = floatval(Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_CUTI));
        $tax = floatval(Setting::getBaseSetting(Setting::INDEX_TAX));
        $indexCutiBesar = floatval(Setting::getBaseSetting(Setting::INDEX_CUTI_BESAR));

        //BPJS
        $maxJP = floatval(Setting::getBaseSetting(Setting::INDEX_JP_MAX));
        $maxJkes = floatval(Setting::getBaseSetting(Setting::INDEX_JKES_MAX));
        $iuranKes = floatval(Setting::getBaseSetting(Setting::IURAN_KES));
        $iuranJHT = floatval(Setting::getBaseSetting(Setting::IURAN_JHT));
        $iuranJP = floatval(Setting::getBaseSetting(Setting::IURAN_JP));
        $iuranJKK = floatval(Setting::getBaseSetting(Setting::IURAN_JKK));
        $iuranJKM = floatval(Setting::getBaseSetting(Setting::IURAN_JKM));

        //value PMK Structure
        $indexPMK5 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_5));
        $indexPMK10 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_10));
        $indexPMK15 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_15));
        $indexPMK20 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_20));
        $indexPMK25 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_25));
        $indexPMK30 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_30));

        //get projection period
        $getSimulation = Simulation::find()->where(['id' => $simId])->one();
        $tahun = date("Y", strtotime($getSimulation->start_date));
        $startMonth = date('Y-m-d', strtotime($tahun . '-' . $bulan));

        $endMonth = date("n", strtotime($getSimulation->end_date));
        $promosiStartDate = date('Y-m-d', strtotime("+6 month", strtotime($startMonth)));
        $startMonthPromosi = $bulan + 6;
        $startMonthProbation = $bulan + 3;

        $tahunMasuk = date('Y-m', strtotime($tahun . '-' . $bulan));


        $dates = Helpers::getMonthIterator($startMonth, $getSimulation->end_date);


        //ambil tabel gaji
        $gajiTableBase = MstGaji::find()->asArray()->all();
        $keyBase = array_search($this->bi, array_column($gajiTableBase, 'bi'));
        $theGajiBase = $gajiTableBase[$keyBase];

        //ambil kota
        $theCity = MstCity::find()->asArray()->all();

        //set batch entry
        $batchEntry = [];

        //array tahunan
        $element = [];


        $now = date('Y-m-d');
        $careerPath = BatchEntry::getCareerPath($now, $getSimulation->end_date);

        $maxGaji = $theGajiBase['gaji_dasar'];
        $maxTbh = $theGajiBase['tunjangan_biaya_hidup'];
        $maxRekomposisi = $theGajiBase['tunjangan_rekomposisi'];
        $maxTunjab = empty($theGajiBase['tunjangan_jabatan_struktural']) ? $theGajiBase['tunjangan_jabatan_functional'] : $theGajiBase['tunjangan_jabatan_struktural'];

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

            $theMonth = intval(substr($dateFmt, 4, 2));


            //hitung masa kerja karyawan
            $currentYear = $date->format('Y-m');
            $diff = (new DateTime($tahunMasuk))->diff(new DateTime($currentYear));
            $masaKerja = $diff->m + $diff->y * 12;

            //bulan ke 7 trainee naik menjadi normal
            if ($this->mstType->type == 'TRAINEE') {
                if ($theMonth >= $startMonthPromosi) {
                    $batchEntry[$dateFmt]['GAJI DASAR'] = $this->naikTrainee->gaji_dasar;
                    $batchEntry[$dateFmt]['TBH'] = $this->naikTrainee->tunjangan_biaya_hidup;
                    $batchEntry[$dateFmt]['REKOMPOSISI'] = $this->naikTrainee->tunjangan_rekomposisi;
                    $batchEntry[$dateFmt]['TOTAL'] = $batchEntry[$dateFmt]['GAJI DASAR'] + $batchEntry[$dateFmt]['TBH'] + $batchEntry[$dateFmt]['REKOMPOSISI'];

                    //total gaji tiap orang per bulan&tahun simulation
                    $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] = $this->naikTrainee->gaji_dasar + $this->naikTrainee->tunjangan_biaya_hidup + $this->naikTrainee->tunjangan_rekomposisi;

                } else {
                    $batchEntry[$dateFmt]['GAJI DASAR'] = $this->trainee ;
                    $batchEntry[$dateFmt]['TBH'] = 0;
                    $batchEntry[$dateFmt]['REKOMPOSISI'] = 0;
                    $batchEntry[$dateFmt]['TOTAL'] = $batchEntry['GAJI DASAR'] + $batchEntry['TBH'] + $batchEntry['REKOMPOSISI'];

                    //total gaji tiap orang per bulan&tahun simulation
                    $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] = $this->trainee + 0 + 0;

                }

            }
            else if ($this->mstType->type == 'PROBATION') { //PROBATION
                if ($theMonth >= $startMonthProbation) {
                    $batchEntry[$dateFmt]['GAJI DASAR'] = $this->salaryStructure;
                    $batchEntry[$dateFmt]['TBH'] = $this->tbhStructure;
                    $batchEntry[$dateFmt]['REKOMPOSISI'] = $this->rekomposisiStructure;
                    $batchEntry[$dateFmt]['TOTAL'] = $batchEntry['GAJI DASAR'] + $batchEntry['TBH'] + $batchEntry['REKOMPOSISI'];

                    //total gaji tiap orang per bulan&tahun simulation
                    $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] = $this->salaryStructure + $this->tbhStructure + $this->rekomposisiStructure;
                } else {
                    $batchEntry[$dateFmt]['GAJI DASAR'] = $this->salaryStructure * 0.9;
                    $batchEntry[$dateFmt]['TBH'] = $this->tbhStructure * 0.9;
                    $batchEntry[$dateFmt]['REKOMPOSISI'] = $this->rekomposisiStructure * 0.9;
                    $batchEntry[$dateFmt]['TOTAL'] = $batchEntry['GAJI DASAR'] + $batchEntry['TBH'] + $batchEntry['REKOMPOSISI'];

                    //total gaji tiap orang per bulan&tahun simulation
                    $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] = ($this->salaryStructure * 0.9) + ($this->tbhStructure * 0.9) + ($this->rekomposisiStructure * 0.9);
                }
            }
            else if ($this->mstType->type == 'PROMOSI') {

                // ambil gaji
                $oldBi = 'N/A';
                if ($this->bi == $careerPath['path'][$dateFmt]['bi']) {

                    $gaji = $prevGajiDasar;
                    $tbh = $prevTbh;
                    $rekomposisi = $prevRekomposisi;
                    $tunjab = $prevTunjab;

                } else {
                    $oldBi = $careerPath['path'][$prevDateFmt]['bi'];
                    if ($oldBi == $this->bi) {
                        $oldGaji = $prevGajiDasar;
                        $oldTbh = $prevTbh;
                        $oldRekomposisi = $prevRekomposisi;
                        $oldTunjab = $prevTunjab;
                    } else {

                        $key = array_search($oldBi, array_column($gajiTableBase, 'bi'));
                        $theOldGaji = $gajiTableBase[$key];
                        $oldGaji = $theOldGaji['gaji_dasar'];
                        $oldTbh = $theOldGaji['tunjangan_biaya_hidup'];
                        $oldRekomposisi = $theOldGaji['tunjangan_rekomposisi'];
                        $oldTunjab = empty($theOldGaji['tunjangan_jabatan_struktural']) ? $theOldGaji['tunjangan_jabatan_functional'] : $theOldGaji['tunjangan_jabatan_struktural'];

                    }

                    $key = array_search($careerPath['path'][$dateFmt]['bi'], array_column($gajiTableBase, 'bi'));
                    $theGaji = $gajiTableBase[$key];

                    $gaji = $theGaji['gaji_dasar'];
                    $tbh = $theGaji['tunjangan_biaya_hidup'];
                    $rekomposisi = $theGaji['tunjangan_rekomposisi'];
                    $tunjab = empty($theGaji['tunjangan_jabatan_struktural']) ? $theGaji['tunjangan_jabatan_functional'] : $theGaji['tunjangan_jabatan_struktural'];

                }

                if ($careerPath['path'][$dateFmt]['is_naik_bi']) {
                    if ($prevGajiDasar > $gaji) {
                        echo "\n===========\nnew Gaji Dasar is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevGajiDasar) . ' (from mst_gaji table, BI: ' . $oldBi . ') to ' . number_format($gaji) . " (from mst_gaji table, BI: " . $careerPath['path'][$dateFmt]['bi'] . ")\n";
                        $gaji = $prevGajiDasar;
                    }

                    if ($maxGaji > $gaji) {
                        $gaji = $maxGaji;
                    }

                    if ($prevTbh > $tbh) {
                        echo "\n===========\nnew TBH is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTbh) . ' (from mst_gaji table, BI: ' . $oldBi . ') to ' . number_format($tbh) . " (from mst_gaji table, BI: " . $careerPath['path'][$dateFmt]['bi'] . ") \n";
                        $tbh = $prevTbh;
                    }

                    if ($maxTbh > $tbh) {
                        $tbh = $maxTbh;
                    }

                    if ($prevRekomposisi > $rekomposisi) {
                        echo "\n===========\nnew REkomposisi is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevRekomposisi) . ' (from mst_gaji table, BI: ' . $oldBi . ') to ' . number_format($rekomposisi) . " (from mst_gaji table, BI: " . $careerPath['path'][$dateFmt]['bi'] . ") \n";
                        $rekomposisi = $prevRekomposisi;
                    }

                    if ($maxRekomposisi > $rekomposisi) {
                        $rekomposisi = $maxRekomposisi;
                    }

                    if ($prevTunjab > $tunjab) {
                        echo "\n===========\nnew Tunjangan Jabatan is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTunjab) . ' (from mst_gaji table, BI: ' . $oldBi . ') to ' . number_format($tunjab) . " (from mst_gaji table, BI: " . $careerPath['path'][$dateFmt]['bi'] . ") \n";
                        $tunjab = $prevTunjab;
                    }

                    if ($maxTunjab > $tunjab) {
                        $tunjab = $maxTunjab;
                    }

                    $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['gaji dasar'] = $gaji - $prevGajiDasar;
                    $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tbh'] = $tbh - $prevTbh;
                    $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['rekomposisi'] = $rekomposisi - $prevRekomposisi;
                    $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tunjangan jabatan'] = $tunjab - $prevTunjab;

                    $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['total'] = ($gaji - $prevGajiDasar) + ($tbh - $prevTbh) + ($rekomposisi - $prevRekomposisi) + ($tunjab - $prevTunjab);
                    $total = $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['total'];

                    //element bpjs ketenagakerjaan
                    $totalJHT = $iuranJHT * $total;
                    //validate max upah untuk Iuran JP
                    if ($total >= $maxJP) {
                        $totalJP = $iuranJP * floatval($maxJP);
                    } else {
                        $totalJP = $iuranJP * $total;
                    }
                    $totalJKK = $iuranJKK * $total;
                    $totalJKM = $iuranJKM * $total;


                    //element bpjs kesehatan
                    //validate max upah untuk Iuran JKes
                    if ($total >= $maxJkes) {
                        $totalKes = $iuranKes * floatval($maxJkes);
                    } else {
                        $totalKes = $iuranKes * $total;
                    }

                    $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['EMPLOYEES BPJS'] = ($totalJHT + $totalJP + $totalJKK + $totalJKM) + $totalKes ;

                    //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                    $batchEntry[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['EMPLOYEE INCOME TAX'] = (($total + ($tunjab - $prevTunjab)) / (1 - $tax)) * $tax;

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


                $batchEntry[$dateFmt]['GAJI DASAR'] = $gaji;
                $batchEntry[$dateFmt]['TBH'] = $tbh;
                $batchEntry[$dateFmt]['REKOMPOSISI'] = $rekomposisi;
                $batchEntry[$dateFmt]['TUNJAB'] = $tunjab;
                $batchEntry[$dateFmt]['TOTAL'] = $batchEntry['GAJI DASAR'] + $batchEntry[$dateFmt]['TBH'] + $batchEntry[$dateFmt]['REKOMPOSISI'] + $batchEntry[$dateFmt]['TUNJAB'];

                //total gaji tiap orang per bulan&tahun simulation
                $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] = $batchEntry[$dateFmt]['TOTAL'] - $batchEntry[$dateFmt]['TUNJAB'];


            }
            else if ($this->mstType->type == 'ROTASI') {
                $batchEntry[$dateFmt]['GAJI DASAR'] = $this->salaryStructure;
                $batchEntry[$dateFmt]['TBH'] = $this->tbhStructure;
                $batchEntry[$dateFmt]['REKOMPOSISI'] = $this->rekomposisiStructure;
                $batchEntry[$dateFmt]['TUNJAB'] = $this->tunjabRotasiStructure;
                $batchEntry[$dateFmt]['TOTAL'] = $batchEntry[$dateFmt]['GAJI DASAR'] + $batchEntry[$dateFmt]['TBH'] + $batchEntry[$dateFmt]['REKOMPOSISI'] + $batchEntry[$dateFmt]['TUNJAB'];

                //total gaji tiap orang per bulan&tahun simulation
                $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] = $this->salaryStructure + $this->tbhStructure + $this->rekomposisiStructure;
            }
            else {
                $batchEntry[$dateFmt]['GAJI DASAR'] = $this->salaryStructure;
                $batchEntry[$dateFmt]['TBH'] = $this->tbhStructure;
                $batchEntry[$dateFmt]['REKOMPOSISI'] = $this->rekomposisiStructure;
                $batchEntry[$dateFmt]['TOTAL'] = $batchEntry[$dateFmt]['GAJI DASAR'] + $batchEntry[$dateFmt]['TBH'] + $batchEntry[$dateFmt]['REKOMPOSISI'];

                //total gaji tiap orang per bulan&tahun simulation
                $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] = $this->salaryStructure + $this->tbhStructure + $this->rekomposisiStructure;
            }


            if ($this->mstType->type == 'PROHIRE') { //PROHIRE
                if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
                    $batchEntry[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($this->perc_inc_gadas) ? 0 : $batchEntry[$dateFmt]['GAJI DASAR'] * $this->perc_inc_gadas / 100);
                    $batchEntry[$dateFmt]['KENAIKAN TBH'] =
                        (empty($this->perc_inc_tbh) ? 0 : $batchEntry[$dateFmt]['TBH'] * $this->perc_inc_tbh / 100);
                    $batchEntry[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($this->perc_inc_rekomposisi) ? 0 : $batchEntry[$dateFmt]['REKOMPOSISI'] * $this->perc_inc_rekomposisi / 100);
                    $batchEntry[$dateFmt]['TOTAL KENAIKAN'] = $batchEntry[$dateFmt]['KENAIKAN GADAS'] + $batchEntry[$dateFmt]['KENAIKAN TBH'] + $batchEntry[$dateFmt]['KENAIKAN REKOMPOSISI'];
                }
            }

            $batchEntry[$dateFmt]['TOTAL'] = ($batchEntry[$dateFmt]['GAJI DASAR'] + $batchEntry[$dateFmt]['TBH'] + $batchEntry[$dateFmt]['REKOMPOSISI']) +
                (empty($batchEntry[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$dateFmt]['TOTAL KENAIKAN']);


            //element bpjs ketenagakerjaan
            $totalJHT = $iuranJHT * $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'];
            //validate max upah untuk Iuran JP
            if ($batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                $totalJP = $iuranJP * floatval($maxJP);
            } else {
                $totalJP = $iuranJP * $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'];
            }
            $totalJKK = $iuranJKK * $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'];
            $totalJKM = $iuranJKM * $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'];


            //element bpjs kesehatan
            //validate max upah untuk Iuran JKes
            if ($batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJkes) {
                $totalKes = $iuranKes * floatval($maxJkes);
            } else {
                $totalKes = $iuranKes * $batchEntry[$dateFmt]['TOTAL GAJI INDIVIDU'];
            }

            if ($this->mstType->type != 'PROMOSI') {
            $batchEntry[$dateFmt]['BPJS KETENEGAKERJAAN'] = ($totalJHT + $totalJP + $totalJKK + $totalJKM);
            $batchEntry[$dateFmt]['BPJS KESEHATAN'] = $totalKes;

            //$batchEntry[$dateFmt]['EMPLOYEE INCOME TAX'] = $tax * $batchEntry[$dateFmt]['TOTAL'];

            //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
            $batchEntry[$dateFmt]['EMPLOYEE INCOME TAX'] = (($batchEntry[$dateFmt]['TOTAL'] + $batchEntry[$dateFmt]['TUNJANGAN JABATAN']) / (1 - $tax)) * $tax;
            }

            //PMK
            if ($this->mstType->type == 'PROBATION') {
                if ($masaKerja == 60) {
                    $batchEntry[$dateFmt]['PMK'] = $indexPMK5 * $batchEntry[$dateFmt]['TOTAL'];
                } else if ($masaKerja == 120) {
                    $batchEntry[$dateFmt]['PMK'] = $indexPMK10 * $batchEntry[$dateFmt]['TOTAL'];
                } else if ($masaKerja == 180) {
                    $batchEntry[$dateFmt]['PMK'] = $indexPMK15 * $batchEntry[$dateFmt]['TOTAL'];
                } else if ($masaKerja == 240) {
                    $batchEntry[$dateFmt]['PMK'] = $indexPMK20 * $batchEntry[$dateFmt]['TOTAL'];
                } else if ($masaKerja == 300) {
                    $batchEntry[$dateFmt]['PMK'] = $indexPMK25 * $batchEntry[$dateFmt]['TOTAL'];
                } else if ($masaKerja == 360) {
                    $batchEntry[$dateFmt]['PMK'] = $indexPMK30 * $batchEntry[$dateFmt]['TOTAL'];
                } else {
                    $batchEntry[$dateFmt]['PMK'] = 0;
                }

                //CUTI BESAR
                if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                    $batchEntry[$dateFmt]['CUTI BESAR'] = $indexCutiBesar * $batchEntry[$dateFmt]['TOTAL'];
                } else {
                    $batchEntry[$dateFmt]['CUTI BESAR'] = 0;
                }
            }

        }

        $start = current(array_keys($batchEntry));
        $end = end(array_keys($batchEntry));

        $theFirstYear = intval(substr($start, 0, 4));
        $theLastYear = intval(substr($end, 0, 4));

        $theFirstMonth = 12;
        $theFirstMonths = intval(substr($start, 4, 2));
        $theMonths = substr($end, 4, 2);
        $theLastMonth = intval(substr($end, 4, 2));


        for ($i = $theFirstYear; $i <= $theLastYear; $i++) {

            if ($i < $theLastYear) {

                //echo "pakai". $theFirstMonth."\n";
                if ($this->mstType->type == 'ROTASI') {
                $element[$i . $theFirstMonth]['LAST TOTAL'] = $batchEntry[$i . $theFirstMonth]['GAJI DASAR'] + $batchEntry[$i . $theFirstMonth]['TBH'] + $batchEntry[$i . $theFirstMonth]['REKOMPOSISI'] + $batchEntry[$i . $theFirstMonth]['TUNJAB'] + (empty($batchEntry[$i . $theFirstMonth]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i . $theFirstMonth]['TOTAL KENAIKAN']);
                }
                else {
                $element[$i . $theFirstMonth]['LAST TOTAL'] = $batchEntry[$i . $theFirstMonth]['GAJI DASAR'] + $batchEntry[$i . $theFirstMonth]['TBH'] + $batchEntry[$i . $theFirstMonth]['REKOMPOSISI'] + (empty($batchEntry[$i . $theFirstMonth]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i . $theFirstMonth]['TOTAL KENAIKAN']);
                }
                $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;

                //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX'] = (($element[$i . $theFirstMonth]['THR'] + $element[$i . $theFirstMonth]['CUTI TAHUNAN']) / (1 - $tax)) * $tax;

            } else {

                //echo "pakai". $theLastMonth."\n";
                if ($this->mstType->type == 'ROTASI') {
                    $element[$i . $theMonths]['LAST TOTAL'] = $batchEntry[$i . $theMonths]['GAJI DASAR'] + $batchEntry[$i . $theMonths]['TBH'] + $batchEntry[$i . $theMonths]['REKOMPOSISI'] + $batchEntry[$i . $theMonths]['TUNJAB'] + (empty($batchEntry[$i . $theMonths]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i . $theMonths]['TOTAL KENAIKAN']);
                }
                else {
                    $element[$i . $theMonths]['LAST TOTAL'] = $batchEntry[$i . $theMonths]['GAJI DASAR'] + $batchEntry[$i . $theMonths]['TBH'] + $batchEntry[$i . $theMonths]['REKOMPOSISI'] + (empty($batchEntry[$i . $theMonths]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i . $theMonths]['TOTAL KENAIKAN']);
                }

                $element[$i . $theMonths]['THR'] = (($indexTHR * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);

                //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                $element[$i . $theMonths]['EMPLOYEE INCOME TAX'] = (($element[$i . $theMonths]['THR'] + $element[$i . $theMonths]['CUTI TAHUNAN']) / (1 - $tax)) * $tax;

            }

        }

        for ($i = $theFirstYear; $i <= $theLastYear; $i++) {
            if ($i < $theLastYear) {
                for ($y = $theFirstMonths; $y <= $theFirstMonth; $y++) {
                    $this->setElement($simId, $y, $theFirstYear, 'EMPLOYEES INCOME TAX', $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX'] * $this->jumlah_orang);
                    $this->setElement($simId, $y, $theFirstYear, 'OTHER ALLOWANCE', ($element[$i . $theFirstMonth]['THR'] + $element[$i . $theFirstMonth]['CUTI TAHUNAN']) * $this->jumlah_orang);

                    //$this->setElement($simId, $y, $theFirstYear, 'THR', $element[$i . $theFirstMonth]['THR']);
                    //$this->setElement($simId, $y, $theFirstYear, 'CUTI TAHUNAN', $element[$i . $theFirstMonth]['CUTI TAHUNAN']);

                }
            } else {
                for ($y = $theFirstMonths; $y <= $theLastMonth; $y++) {
                    $this->setElement($simId, $y, $theLastYear, 'EMPLOYEES INCOME TAX', $element[$i . $theMonths]['EMPLOYEE INCOME TAX'] * $this->jumlah_orang);
                    $this->setElement($simId, $y, $theLastYear, 'OTHER ALLOWANCE', ($element[$i . $theMonths]['THR'] + $element[$i . $theMonths]['CUTI TAHUNAN']) * $this->jumlah_orang);

                    //$this->setElement($simId, $y, $theLastYear, 'THR', $element[$i . $theMonths]['THR']);
                    //$this->setElement($simId, $y, $theLastYear, 'CUTI TAHUNAN', $element[$i . $theMonths]['CUTI TAHUNAN']);

                }
            }
        }

        foreach ($batchEntry as $i => $rows) {

            $theYear = substr($i, 0, 4);
            $theMonth = substr($i, 4, 2);


            if ($this->mstType->type == 'PROMOSI') {
                if (!empty($batchEntry[$i]['SELISIH'])) {
                    foreach ($batchEntry[$i]['SELISIH'] as $row => $y) {
                        $this->setElement($simId, $theMonth, $theYear, 'BASE SALARIES', $y['gaji dasar'] * $this->jumlah_orang + (empty($batchEntry[$i]['KENAIKAN GADAS']) ? 0 : $batchEntry[$i]['KENAIKAN GADAS']));
                        $this->setElement($simId, $theMonth, $theYear, 'FUNCTIONAL ALLOWANCES', $y['tunjangan jabatan'] * $this->jumlah_orang);
                        $this->setElement($simId, $theMonth, $theYear, 'LIVING COST ALLOWANCES', $y['tbh'] * $this->jumlah_orang + (empty($batchEntry[$i]['KENAIKAN TBH']) ? 0 : $batchEntry[$i]['KENAIKAN TBH']));
                        $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES INCOME TAX', $y['EMPLOYEE INCOME TAX'] * $this->jumlah_orang);
                        $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES BPJS', $y['EMPLOYEES BPJS'] * $this->jumlah_orang + (empty($batchEntry[$i]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i]['TOTAL KENAIKAN']));
                        $this->setElement($simId, $theMonth, $theYear, 'TUNJANGAN REKOMPOSISI',  $y['rekomposisi'] * $this->jumlah_orang + (empty($batchEntry[$i]['KENAIKAN REKOMPOSISI']) ? 0 : $batchEntry[$i]['KENAIKAN REKOMPOSISI']));
                        $this->setElement($simId, $theMonth, $theYear, 'PENGHARGAAN MASA KERJA', $batchEntry[$i]['PMK'] * $this->jumlah_orang);
                        $this->setElement($simId, $theMonth, $theYear, 'CUTI BESAR', $batchEntry[$i]['CUTI BESAR'] * $this->jumlah_orang);
                    }
                }
                else {
                    $this->setElement($simId, $theMonth, $theYear, 'BASE SALARIES', 0);
                    $this->setElement($simId, $theMonth, $theYear, 'FUNCTIONAL ALLOWANCES', 0);
                    $this->setElement($simId, $theMonth, $theYear, 'LIVING COST ALLOWANCES', 0);
                    $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES INCOME TAX', 0);
                    $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES BPJS', 0);
                    $this->setElement($simId, $theMonth, $theYear, 'TUNJANGAN REKOMPOSISI',  0);
                    $this->setElement($simId, $theMonth, $theYear, 'PENGHARGAAN MASA KERJA', 0);
                    $this->setElement($simId, $theMonth, $theYear, 'CUTI BESAR', 0);
                }
            }
            else {
            $this->setElement($simId, $theMonth, $theYear, 'BASE SALARIES', $batchEntry[$i]['GAJI DASAR'] * $this->jumlah_orang + (empty($batchEntry[$i]['KENAIKAN GADAS']) ? 0 : $batchEntry[$i]['KENAIKAN GADAS']));
            $this->setElement($simId, $theMonth, $theYear, 'FUNCTIONAL ALLOWANCES', $batchEntry[$i]['TUNJAB'] * $this->jumlah_orang);
            $this->setElement($simId, $theMonth, $theYear, 'LIVING COST ALLOWANCES', $batchEntry[$i]['TBH'] * $this->jumlah_orang + (empty($batchEntry[$i]['KENAIKAN TBH']) ? 0 : $batchEntry[$i]['KENAIKAN TBH']));
            $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES INCOME TAX', $batchEntry[$i]['EMPLOYEE INCOME TAX'] * $this->jumlah_orang);
            $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES BPJS', ($batchEntry[$i]['BPJS KESEHATAN'] + $batchEntry[$i]['BPJS KETENEGAKERJAAN']) * $this->jumlah_orang + (empty($batchEntry[$i]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i]['TOTAL KENAIKAN']));

            //$this->setElement($simId, $theMonth, $theYear, 'BPJS KESEHATAN', $batchEntry[$i]['BPJS KESEHATAN'] + (empty($batchEntry[$i]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i]['TOTAL KENAIKAN']));
            //$this->setElement($simId, $theMonth, $theYear, 'BPJS KETENEGAKERJAAN', $batchEntry[$i]['BPJS KETENEGAKERJAAN'] + (empty($batchEntry[$i]['TOTAL KENAIKAN']) ? 0 : $batchEntry[$i]['TOTAL KENAIKAN']));


            $this->setElement($simId, $theMonth, $theYear, 'TUNJANGAN REKOMPOSISI', $batchEntry[$i]['REKOMPOSISI'] * $this->jumlah_orang + (empty($batchEntry[$i]['KENAIKAN REKOMPOSISI']) ? 0 : $batchEntry[$i]['KENAIKAN REKOMPOSISI']));

            $this->setElement($simId, $theMonth, $theYear, 'PENGHARGAAN MASA KERJA', $batchEntry[$i]['PMK'] * $this->jumlah_orang);
            $this->setElement($simId, $theMonth, $theYear, 'CUTI BESAR', $batchEntry[$i]['CUTI BESAR'] * $this->jumlah_orang);

            }

        }

        Yii::$app->session->remove('sessionSimId');
        Yii::$app->session->remove('sessionBp');
        Yii::$app->session->remove('sessionBi');
        Yii::$app->session->remove('keterangan');

        var_dump($batchEntry, $element);

        return $batchEntry;
    }


    public
    function setCreateBatch($simId)
    {

        //$simulationId = Yii::$app->getRequest()->getQueryParam('simId');

        $getSimulation = Simulation::find()->where(['id' => $simId])->one();

        $startMonth = date("n", strtotime($getSimulation->start_date));
        $endMonth = date("n", strtotime($getSimulation->end_date));
        $tahun = date("Y", strtotime($getSimulation->start_date));
        $getperiod = ($endMonth - $startMonth) + 1;
        $period = ($endMonth - $startMonth) + 2;

        $xx = Yii::$app->request->post();

        // cari maks jenis filter yg dikirim
        $mentok = false;
        $highestIndex = 0;
        $currentIndex = 0;
        $successSave = false;
        $newFilterIds = [];

        $bulan = intval($xx['BatchEntry'][$currentIndex]['bulan']);
        $percentage = intval($xx['BatchEntry'][$currentIndex]['percentage']);
        $amount = floatval($xx['BatchEntry'][$currentIndex]['nilai']);

        $typeFilter = $xx['BatchEntry']['type_filter'];
        $totalPercentage = 0;
        $count = 0;

        //store to table

        if ((isset($xx['BatchEntry'][$currentIndex])) && ($typeFilter == 'PERCENTAGE') && ($bulan != null) && ($percentage != null)) {
            echo "filter percentage tidak kosong <br/>";

            while (!$mentok) {

                $maxPercentage = 100;

                $bulan = intval($xx['BatchEntry'][$currentIndex]['bulan']);
                $percentage = intval($xx['BatchEntry'][$currentIndex]['percentage']);
                $count++;

                $totalPercentage += floatval($xx['BatchEntry'][$currentIndex]['percentage']);
                $sisa = $maxPercentage - $totalPercentage;

                if ((isset($xx['BatchEntry'][$currentIndex])) && ($totalPercentage < $maxPercentage)) {
                    $highestIndex = $currentIndex;

                    $newFilter = new SimulationDetail();
                    $newFilter->load($xx);
                    $newFilter->bulan = $bulan;
                    $newFilter->tahun = $tahun;
                    $newFilter->amount = ((int)$this->amount * $percentage / 100);
                    $newFilter->element = $this->mstElement->element_name;
                    $newFilter->save();

                    $newFilterIds[] = $newFilter->id;
                    $currentIndex++;
                    $successSave = true;
                } else {
                    $mentok = true;

                    for ($i = $count; $i <= $endMonth; $i++) {
                        $data = new SimulationDetail();
                        $data->simulation_id = $simId;
                        $data->bulan = $i;
                        $data->tahun = $tahun;
                        $data->amount = (($this->amount * $sisa / 100) / ($period - $count) * 1);
                        $data->element = $this->mstElement->element_name;
                        $data->save();
                    }
                }
            }

        } else if ((isset($xx['BatchEntry'][$currentIndex])) && ($typeFilter == 'AMOUNT') && ($bulan != null) && ($amount != null)) {
            echo "filter nilai tidak kosong <br/>";

            while (!$mentok) {

                $maxNilai = floatval($xx['BatchEntry']['amount']);

                $bulan = intval($xx['BatchEntry'][$currentIndex]['bulan']);
                $amount = str_replace(",", "", $xx['BatchEntry'][$currentIndex]['nilai']);
                $count++;

                $totalAmount += floatval($amount);
                $sisa = $maxNilai - $totalAmount;

                if ((isset($xx['BatchEntry'][$currentIndex])) && ($totalAmount > 0)) {
                    $highestIndex = $currentIndex;

                    $newFilter = new SimulationDetail();
                    $newFilter->load($xx);
                    $newFilter->bulan = $bulan;
                    $newFilter->tahun = $tahun;
                    $newFilter->amount = $amount;
                    $newFilter->element = $this->mstElement->element_name;
                    $newFilter->save();

                    $newFilterIds[] = $newFilter->id;
                    $currentIndex++;
                    $successSave = true;
                } else {
                    $mentok = true;

                    for ($i = $count; $i <= $endMonth; $i++) {
                        $data = new SimulationDetail();
                        $data->simulation_id = $simId;
                        $data->bulan = $i;
                        $data->tahun = $tahun;
                        $data->amount = (($sisa) / ($period - $count) * 1);
                        $data->element = $this->mstElement->element_name;
                        $data->save();
                    }
                }
            }

        } else {
            for ($i = $startMonth; $i <= $endMonth; $i++) {
                echo "filter kosong <br/>";
                $data = new SimulationDetail();
                $data->simulation_id = $simId;
                $data->bulan = $i;
                $data->tahun = $tahun;
                $data->amount = ((int)$this->amount / $getperiod * 1);
                $data->element = $this->mstElement->element_name;
                $data->save();
            }

        }

    }


}
