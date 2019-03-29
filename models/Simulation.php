<?php

namespace reward\models;

use DateTime;
use reward\components\Helpers;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "simulation".
 *
 * @property string $id
 * @property string $start_date
 * @property string $end_date
 * @property string $effective_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property SimulationDetail[] $simulationDetails
 * @property  nik
 * @property mixed perc_inc_gadas
 * @property mixed perc_inc_tbh
 * @property mixed perc_inc_rekomposisi
 * @property mixed description
 * @property mixed created_by
 */
class Simulation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $nik;
    public $type;
    public $element;
    public $type_element;

    public static function tableName()
    {
        return 'simulation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'required'],
            [['start_date', 'end_date', 'effective_date', 'created_at', 'updated_at', 'nik', 'created_by'], 'safe'],
//            [['start_date'], 'unique'],
            [['status'], 'string'],
            [['description'], 'string', 'max' => 100],
            [['perc_inc_gadas', 'perc_inc_tbh', 'perc_inc_rekomposisi'], 'number', 'max' => 99.9, 'min' => 1],
            ['end_date', 'compare', 'compareAttribute' => 'start_date', 'operator' => '>', 'skipOnEmpty' => true, 'message' => '{attribute} must be greater than "{compareValue}".'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'effective_date' => 'Effective Date',
            'perc_inc_gadas' => 'Kenaikan Gaji Dasar (%)',
            'perc_inc_tbh' => 'Kenaikan TBH (%)',
            'perc_inc_rekomposisi' => 'Kenaikan Tunjangan Rekomposisi (%)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSimulationDetails()
    {
        return $this->hasMany(SimulationDetail::className(), ['simulation_id' => 'id']);
    }


    public function beforeSave($insert)
    {


        if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
            $this->perc_inc_gadas = str_replace(",'", ".", $this->perc_inc_gadas);
            $this->perc_inc_tbh = str_replace(",'", ".", $this->perc_inc_tbh);
            $this->perc_inc_rekomposisi = str_replace(",'", ".", $this->perc_inc_rekomposisi);
        }

        if ($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");

        else
            $this->updated_at = date("Y-m-d H:i:s");


        return parent::beforeSave($insert);
    }

    public static function getListSimulations()
    {
        $droptions = Simulation::find()->asArray()->orderBy(['id' => SORT_DESC])->all();
        return ArrayHelper::map($droptions, 'id', function ($model) {
            return date("d-M-Y", strtotime($model['start_date'])) . ' s/d ' . date("d-M-Y", strtotime($model['end_date'])) . ' (' . substr($model['description'], 0, 20) . ')';
        });
    }

    public function setBatchDetail($bulan, $tahun, $element, $description, $amount, $nik)
    {

        $batch = new BatchDetail();
        $batch->simulation_id = $this->id;
        $batch->bulan = $bulan;
        $batch->tahun = $tahun;
        $batch->element = $element;
        $batch->description = $description;
        $batch->amount = $amount;
        $batch->nik = $nik;
        $batch->save();

    }


    public function setElement($bulan, $tahun, $element, $amount)
    {

        $simulation = new SimulationDetail();
        $simulation->simulation_id = $this->id;
        $simulation->bulan = $bulan;
        $simulation->tahun = $tahun;
        $simulation->element = $element;
        $simulation->amount = $amount;
        $simulation->save();
    }


    public function setBasicElementsNew()
    {
        //get value from setting model
        $indexTHR = floatval(Setting::getBaseSetting(Setting::INDEX_THR_1));
        $indexTHR2 = floatval(Setting::getBaseSetting(Setting::INDEX_THR_2));
        $indexTA = floatval(Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_AKHIR_TAHUN));
        $indexUangSakuAP = floatval(Setting::getBaseSetting(Setting::INDEX_UANG_SAKU_AKHIR_PROGRAM));

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

        //IE
        $indexIE1 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_1));
        $indexIE2 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_2));
        $indexIE3 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_3));
        $indexIE4 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_4));
        $indexIE5 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_5));
        $indexIE6 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_6));
        $indexIEContract = floatval(Setting::getBaseSetting(Setting::INDEX_IE_CONTRACT));
        $indexIETelkom = floatval(Setting::getBaseSetting(Setting::INDEX_IE_TELKOM));

        //INSENTIF SEMESTERAN
        $indexISTelkom = floatval(Setting::getBaseSetting(Setting::INDEX_IS_TELKOM));
        $indexISContractProf = floatval(Setting::getBaseSetting(Setting::INDEX_IS_CONTRACT_PROF));


        //$nikYangDimaksud = ['T118001', '87039', '71206', '86068', '86065', '88003', '214046', '79205'];

        // ambil semua nik unik
        $niks = Employee::find()->select('nik')
            ->where(['status' => Employee::ACTIVE])
            ->andWhere(['not', ['salary' => NULL]])
            //->andWhere(['nik' => $nikYangDimaksud])
            ->andFilterWhere(['not', ['tunjangan' => NULL]])
            ->andFilterWhere(['not', ['tunjangan_rekomposisi' => NULL]])
            ->distinct()
            ->all();

        // ambil semua career path employee
        $data = [];

        $dates = Helpers::getMonthIterator($this->start_date, $this->end_date);

        //array tahun
        $element = [];
        $start = current(array_keys($data));
        $end = end(array_keys($data));

        $theFirstYear = intval(substr($start, 0, 4));
        $theLastYear = intval(substr($end, 0, 4));

        $theFirstMonth = 12;
        $theFirstMonths = intval(substr($start, 4, 2));
        $theMonths = substr($end, 4, 2);
        $theLastMonth = intval(substr($end, 4, 2));

        // cache tabel gaji
        $gajiTable = MstGaji::find()->asArray()->all();

        //ambil kota
        $theCity = MstCity::find()->asArray()->all();


        $nik = [];

        foreach ($niks as $theNik) {
            // get employee
            $theEmployee = Employee::find()
                ->where(['status' => Employee::ACTIVE])
                ->andWhere(['nik' => $theNik->nik])
                ->orderBy(['start_date_assignment' => SORT_DESC])
                ->one();

            $careerPath = $theEmployee->getCareerPath($theEmployee->start_date_assignment, $this->end_date);

            if ($theEmployee->employee_category == 'PROBATION') {
                $maxGaji = $theEmployee->salary * 0.9;
                $maxTbh = $theEmployee->tunjangan * 0.9;
                $maxRekomposisi = $theEmployee->tunjangan_rekomposisi * 0.9;
                $maxTunjab = $theEmployee->tunjangan_jabatan * 0.9;
            } else {
                $maxGaji = $theEmployee->salary;
                $maxTbh = $theEmployee->tunjangan;
                $maxRekomposisi = $theEmployee->tunjangan_rekomposisi;
                $maxTunjab = $theEmployee->tunjangan_jabatan;
            }

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
                    if ($theEmployee->employee_category == 'PROBATION') {
                        $gaji = $theEmployee->salary * 0.9;
                        $tbh = $theEmployee->tunjangan * 0.9;
                        $rekomposisi = $theEmployee->tunjangan_rekomposisi * 0.9;
                        $tunjab = $theEmployee->tunjangan_jabatan * 0.9;
                    } else {
                        $gaji = $theEmployee->salary;
                        $tbh = $theEmployee->tunjangan;
                        $rekomposisi = $theEmployee->tunjangan_rekomposisi;
                        $tunjab = $theEmployee->tunjangan_jabatan;
                    }
                } else {
                    $oldBi = $careerPath['path'][$prevDateFmt]['bi'];

                    if ($oldBi == $theEmployee->bi) {
                        $oldGaji = $theEmployee->salary;
                        $oldTbh = $theEmployee->tunjangan;
                        $oldRekomposisi = $theEmployee->tunjangan_rekomposisi;
                        $oldTunjab = $theEmployee->tunjangan_jabatan;
                    } else {

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

                    if ($theEmployee->employee_category == 'PROBATION') {
                        $key = array_search($theEmployee->bi, array_column($gajiTable, 'bi'));
                    } else {
                        $key = array_search($careerPath['path'][$dateFmt]['bi'], array_column($gajiTable, 'bi'));
                    }
                    $theGaji = $gajiTable[$key];

                    $keyCity = array_search($theEmployee->kode_kota, array_column($theCity, 'code'));
                    $city = $theCity[$keyCity];

                    $gaji = $theGaji['gaji_dasar'];
                    $tbh = empty($city['idx_tbh']) ? $theGaji['tunjangan_biaya_hidup'] : $theGaji['tunjangan_biaya_hidup'] * $city['idx_tbh'];
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


                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['jumlah_newbi_band_1'] += intval($careerPath['path'][$dateFmt]['new_bi_band_1']);
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['jumlah_newbi_band_others'] += intval($careerPath['path'][$dateFmt]['new_bi_band_others']);
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['nik'][] = $theEmployee->nik;

                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['gaji dasar'] += $gaji - $prevGajiDasar;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tbh'] += $tbh - $prevTbh;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['rekomposisi'] += $rekomposisi - $prevRekomposisi;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tunjangan jabatan'] += $tunjab - $prevTunjab;

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

                //total gaji tiap orang per bulan&tahun simulation
                if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['TOTAL GAJI INDIVIDU'] = $gaji + $tbh;
                } else {
                    $data[$dateFmt]['TOTAL GAJI INDIVIDU'] = $gaji + $tbh + $rekomposisi;
                }
                $data[$dateFmt]['GAJI DASAR'] += $gaji;
                $data[$dateFmt]['TBH'] += $tbh;
                $data[$dateFmt]['REKOMPOSISI'] += $rekomposisi;
                $data[$dateFmt]['TUNJANGAN JABATAN'] += $tunjab;

                if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
                    $data[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($this->perc_inc_gadas) ? 0 : $data[$dateFmt]['GAJI DASAR'] * $this->perc_inc_gadas / 100);
                    $data[$dateFmt]['KENAIKAN TBH'] =
                        (empty($this->perc_inc_tbh) ? 0 : $data[$dateFmt]['TBH'] * $this->perc_inc_tbh / 100);
                    $data[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($this->perc_inc_rekomposisi) ? 0 : $data[$dateFmt]['REKOMPOSISI'] * $this->perc_inc_rekomposisi / 100);
                    $data[$dateFmt]['TOTAL KENAIKAN'] = $data[$dateFmt]['KENAIKAN GADAS'] + $data[$dateFmt]['KENAIKAN TBH'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];
                }

                $data[$dateFmt]['TOTAL GAJI DASAR'] = $data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['KENAIKAN GADAS'];
                $data[$dateFmt]['TOTAL TBH'] = $data[$dateFmt]['TBH'] + $data[$dateFmt]['KENAIKAN TBH'];
                $data[$dateFmt]['TOTAL REKOMPOSISI'] = $data[$dateFmt]['REKOMPOSISI'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];

                $data[$dateFmt]['TOTAL'] = ($data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['TBH'] + $data[$dateFmt]['REKOMPOSISI']) + (empty($data[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $data[$dateFmt]['TOTAL KENAIKAN']);

                //===========================EMPLOYEE BPJS START=========================
                //element bpjs ketenagakerjaan
                //JHT
                if ($theEmployee->employee_category !== 'TRAINEE') {
                    $totalJHT = $iuranJHT * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                //JKEM
                if ($theEmployee->employee_category == 'TRAINEE') {
                    $totalJKM = $iuranJKM * $data[$dateFmt]['TOTAL GAJI DASAR'];
                } else {
                    $totalJKM = $iuranJKM * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                //JKK
                if ($theEmployee->employee_category == 'TRAINEE') {
                    $totalJKK = $iuranJKK * $data[$dateFmt]['TOTAL GAJI DASAR'];
                } else {
                    $totalJKK = $iuranJKM * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                //JP
                if ($theEmployee->employee_category !== 'TRAINEE') {
                    //validate max upah untuk Iuran JP
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                        $totalJP = $iuranJP * floatval($maxJP);
                    } else {
                        $totalJP = $iuranJP * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                }

                //element bpjs kesehatan
                if ($theEmployee->employee_category !== 'TRAINEE') {
                    //validate max upah untuk Iuran JKes
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJkes) {
                        $totalKes = $iuranKes * floatval($maxJkes);
                    } else {
                        $totalKes = $iuranKes * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                }

                $data[$dateFmt]['BPJS KETENEGAKERJAAN'] += ($totalJHT + $totalJP + $totalJKK + $totalJKM);
                $data[$dateFmt]['BPJS KESEHATAN'] += $totalKes;
                //===================================EMPLOYEE BPJS END===================================


                //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                $data[$dateFmt]['EMPLOYEE INCOME TAX'] = (($data[$dateFmt]['TOTAL'] + $data[$dateFmt]['TUNJANGAN JABATAN']) / (1 - $tax)) * $tax;


                //PMK
                if ($theEmployee->employee_category == 'PERMANENT') {
                    //PMK => KELIPATAN 5 TAHUN S/D 30 TAHUN
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

                }

                //CUTI BESAR => 6 TAHUN = 72 BULAN
                if ($theEmployee->employee_category == 'PERMANENT') {
                    if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                        $data[$dateFmt]['CUTI BESAR'] = $indexCutiBesar * $data[$dateFmt]['TOTAL'];
                    }
                }

            }

            $start = current(array_keys($data));
            $end = end(array_keys($data));

            $theFirstYear = intval(substr($start, 0, 4));
            $theLastYear = intval(substr($end, 0, 4));

            $theFirstMonth = 12;
            $theFirstMonths = intval(substr($start, 4, 2));
            $theMonths = substr($end, 4, 2);
            $theLastMonth = intval(substr($end, 4, 2));


            for ($i = $theFirstYear; $i <= $theLastYear; $i++) {

                $currentSemester = ceil(intval($date->format('m')) / 6);  // bulan ini semester berapa?
                $currentYear = intval($date->format('Y'));

                if ($i < $theLastYear) {

                    //Insentif Semesteran
                    $band = intval(substr($careerPath['path'][$i . $theFirstMonth]['bi'], 0, 1));

                    $theInsentif = Insentif::find()->where([
                        'nik' => $theNik->nik,
                        'tahun' => $date->format('Y'),
                    ])
                        ->andWhere(['smt' => $currentSemester])
                        ->andWhere(['tahun' => $currentYear])
                        ->orderBy(['smt' => SORT_DESC])
                        ->one();

                    $nkk = floatval(Insentif::getConvertionNkk($theInsentif->nkk));
                    $nku = floatval(Insentif::getConvertionNkk($theInsentif->nku));
                    $nki = floatval(Insentif::getConvertionNkk($theInsentif->nki));

                    //echo "pakai". $theFirstMonth."\n";
                    $element[$i . $theFirstMonth]['LAST TOTAL'] = $data[$i . $theFirstMonth]['GAJI DASAR'] + $data[$i . $theFirstMonth]['TBH'] + $data[$i . $theFirstMonth]['REKOMPOSISI'] + (empty($data[$i . $theFirstMonth]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theFirstMonth]['TOTAL KENAIKAN']);

                    //THR
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR2 * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * ($element[$i . $theFirstMonth]['LAST TOTAL'] - $data[$i . $theFirstMonth]['REKOMPOSISI'])) / $theFirstMonth;
                    } else if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $data[$i . $theFirstMonth]['GAJI DASAR']) / $theFirstMonth;
                    } else {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    }

                    //CUTI TAHUNAN
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * ($element[$i . $theFirstMonth]['LAST TOTAL'] - $data[$i . $theFirstMonth]['REKOMPOSISI'])) / $theFirstMonth;
                    }

                    //TUNJANGAN AKHIR TAHUN
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theFirstMonth]['TUNJANGAN AKHIR TAHUN'] = ($indexTA * ($element[$i . $theFirstMonth]['LAST TOTAL'] - $data[$i . $theFirstMonth]['REKOMPOSISI'])) / $theFirstMonth;
                    }

                    //UANG SAKU AKHIR PROGRAM
                    if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theFirstMonth]['UANG SAKU AKHIR PROGRAM'] = ($indexUangSakuAP * $data[$i . $theFirstMonth]['GAJI DASAR']) / $theFirstMonth;
                    }

                    //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                    $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX'] = (($element[$i . $theFirstMonth]['THR'] + $element[$i . $theFirstMonth]['CUTI TAHUNAN']) / (1 - $tax)) * $tax / $theFirstMonth;;


                    if ($theInsentif) {
                        if ($theEmployee->employee_category != 'TRAINEE') {
                            //konstanta * nkk * nku * nki * TOTAL
                            if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                                if ($band == (1 || 2 || 3)) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 2.830 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                } else if ($band == 4) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 3.530 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                } else if ($band == 5) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 4.950 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                } else if ($band == 6) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 7.800 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                }
                            } //konstanta * nki * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT') {
                                $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 4.000 * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT PROF' && $theEmployee->employee_category == 'EXPATRIATE') {
                                $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += $indexISContractProf * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'TELKOM') {
                                $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += $indexISTelkom * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;;
                            }
                        }
                    }

                    //Insentif Ekstra
                    if ($theEmployee->employee_category != 'TRAINEE' || $theEmployee->employee_category != 'CONTRACT PROF' || $theEmployee->employee_category != 'EXPATRIATE') {
                        //konstanta * TOTAL
                        if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                            if ($band == 1) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE1 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 2) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE2 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 3) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE3 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 4) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE4 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 5) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE5 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 6) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE6 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            }
                        } //konstanta * TOTAL
                        else if ($theEmployee->employee_category == 'CONTRACT') {
                            $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIEContract * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                        } //konstanta(FREE INPUT) * TOTAL
                        else if ($theEmployee->employee_category == 'TELKOM') {
                            $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIETelkom * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                        }
                    }

                } else {

                    //echo "pakai". $theLastMonth."\n";

                    //Insentif Semesteran
                    $band = intval(substr($careerPath['path'][$i . $theMonths]['bi'], 0, 1));

                    $theInsentif = Insentif::find()->where([
                        'nik' => $theNik->nik,
                        'tahun' => $date->format('Y'),
                    ])
                        ->andWhere(['smt' => $currentSemester])
                        ->andWhere(['tahun' => $currentYear])
                        ->orderBy(['smt' => SORT_DESC])
                        ->one();

                    $nkk = floatval(Insentif::getConvertionNkk($theInsentif->nkk));
                    $nku = floatval(Insentif::getConvertionNkk($theInsentif->nku));
                    $nki = floatval(Insentif::getConvertionNkk($theInsentif->nki));

                    $element[$i . $theMonths]['LAST TOTAL'] = $data[$i . $theMonths]['GAJI DASAR'] + $data[$i . $theMonths]['TBH'] + $data[$i . $theMonths]['REKOMPOSISI'] + (empty($data[$i . $theMonths]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theMonths]['TOTAL KENAIKAN']);


                    //THR
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR2 * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * ($element[$i . $theMonths]['LAST TOTAL'] - $data[$i . $theMonths]['REKOMPOSISI'])) / $theLastMonth) / (12 / $theLastMonth);
                    } else if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * $data[$i . $theMonths]['GAJI DASAR']) / $theLastMonth) / (12 / $theLastMonth);
                    } else {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    }

                    //CUTI TAHUNAN
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * ($element[$i . $theMonths]['LAST TOTAL'] - $data[$i . $theMonths]['REKOMPOSISI'])) / $theLastMonth) / (12 / $theLastMonth);
                    }

                    //TUNJANGAN AKHIR TAHUN
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theMonths]['TUNJANGAN AKHIR TAHUN'] = (($indexTA * ($element[$i . $theMonths]['LAST TOTAL'] - $data[$i . $theMonths]['REKOMPOSISI'])) / $theLastMonth) / (12 / $theLastMonth);
                    }

                    //UANG SAKU AKHIR PROGRAM
                    if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theMonths]['UANG SAKU AKHIR PROGRAM'] = (($indexUangSakuAP * $data[$i . $theMonths]['GAJI DASAR']) / $theLastMonth) / (12 / $theLastMonth);
                    }


                    //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                    $element[$i . $theMonths]['EMPLOYEE INCOME TAX'] = (($element[$i . $theMonths]['THR'] + $element[$i . $theMonths]['CUTI TAHUNAN']) / (1 - $tax)) * $tax / (($theLastMonth) / (12 / $theLastMonth));


                    if ($theInsentif) {
                        if ($theEmployee->employee_category != 'TRAINEE') {
                            //konstanta * nkk * nku * nki * TOTAL
                            if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                                if ($band == (1 || 2 || 3)) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 2.830 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                } else if ($band == 4) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 3.530 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                } else if ($band == 5) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 4.950 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                } else if ($band == 6) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 7.800 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                }
                            } //konstanta * nki * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT') {
                                $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 4.000 * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU']  / (($theLastMonth) / (12 / $theLastMonth));
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT PROF' && $theEmployee->employee_category == 'EXPATRIATE') {
                                $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += $indexISContractProf * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU']  / (($theLastMonth) / (12 / $theLastMonth));
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'TELKOM') {
                                $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += $indexISTelkom * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU']  / (($theLastMonth) / (12 / $theLastMonth));
                            }
                        }
                    }

                    //Insentif Ekstra
                    if ($theEmployee->employee_category != 'TRAINEE' || $theEmployee->employee_category != 'CONTRACT PROF' || $theEmployee->employee_category != 'EXPATRIATE') {
                        //konstanta * TOTAL
                        if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                            if ($band == 1) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE1 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 2) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE2 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 3) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE3 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 4) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE4 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 5) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE5 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 6) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE6 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            }
                        } //konstanta * TOTAL
                        else if ($theEmployee->employee_category == 'CONTRACT') {
                            $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIEContract * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));;
                        } //konstanta(FREE INPUT) * TOTAL
                        else if ($theEmployee->employee_category == 'TELKOM') {
                            $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIETelkom * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));;
                        }
                    }

                }

            }

        }

        for ($i = $theFirstYear; $i <= $theLastYear; $i++) {
            if ($i < $theLastYear) {
                for ($y = $theFirstMonths; $y <= $theFirstMonth; $y++) {
                    $this->setElement($y, $theFirstYear, 'EMPLOYEES INCOME TAX', $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX']);
                    $this->setElement($y, $theFirstYear, 'OTHER ALLOWANCE', $element[$i . $theFirstMonth]['THR'] + $element[$i . $theFirstMonth]['CUTI TAHUNAN']);

                    $this->setElement($y, $theFirstYear, 'TUNJANGAN AKHIR TAHUN', $element[$i . $theFirstMonth]['TUNJANGAN AKHIR TAHUN']);
                    $this->setElement($y, $theFirstYear, 'UANG SAKU AKHIR PROGRAM', $element[$i . $theFirstMonth]['UANG SAKU AKHIR PROGRAM']);

                    $this->setElement($y, $theFirstYear, 'PERFORMANCE INCENTIVE', $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] + $element[$i . $theFirstMonth]['INSENTIF EKSTRA']);

                    //$this->setElement($y, $theFirstYear, 'THR', $element[$i . $theFirstMonth]['THR']);
                    //$this->setElement($y, $theFirstYear, 'CUTI TAHUNAN', $element[$i . $theFirstMonth]['CUTI TAHUNAN']);
                }
            } else {
                for ($y = $theFirstMonths; $y <= $theLastMonth; $y++) {
                    $this->setElement($y, $theLastYear, 'EMPLOYEES INCOME TAX', $element[$i . $theMonths]['EMPLOYEE INCOME TAX']);
                    $this->setElement($y, $theLastYear, 'OTHER ALLOWANCE', $element[$i . $theMonths]['THR'] + $element[$i . $theMonths]['CUTI TAHUNAN']);

                    $this->setElement($y, $theLastYear, 'TUNJANGAN AKHIR TAHUN', $element[$i . $theMonths]['TUNJANGAN AKHIR TAHUN']);
                    $this->setElement($y, $theLastYear, 'UANG SAKU AKHIR PROGRAM', $element[$i . $theMonths]['UANG SAKU AKHIR PROGRAM']);

                    $this->setElement($y, $theLastYear, 'PERFORMANCE INCENTIVE', $element[$i . $theMonths]['INSENTIF SEMESTERAN'] + $element[$i . $theMonths]['INSENTIF EKSTRA']);


                    //$this->setElement($y, $theLastYear, 'THR', $element[$i . $theMonths]['THR']);
                    //$this->setElement($y, $theLastYear, 'CUTI TAHUNAN', $element[$i . $theMonths]['CUTI TAHUNAN']);

                }
            }
        }


        foreach ($data as $i => $rows) {
            $theYear = substr($i, 0, 4);
            $theMonth = substr($i, 4, 2);

            if (!empty($data[$i]['SELISIH'])) {
                foreach ($data[$i]['SELISIH'] as $row => $y) {
                    $array = $y['nik'];

                    //$values = array_map('array_pop', $array);
                    $imploded = implode(',', $array);

                    if ($row == 'EVALUASI') {
                        $this->setBatchDetail($theMonth, $theYear, 'JUMLAH NEW BI', $row, $y['jumlah_newbi_band_others'], $imploded);
                    } else {
                        $this->setBatchDetail($theMonth, $theYear, 'JUMLAH NEW BI', $row, $y['jumlah_newbi_band_1'], $imploded);
                    }

                    $this->setBatchDetail($theMonth, $theYear, 'BASE SALARIES', $row, $y['gaji dasar'], '');
                    $this->setBatchDetail($theMonth, $theYear, 'FUNCTIONAL ALLOWANCES', $row, $y['tunjangan jabatan'], '');
                    $this->setBatchDetail($theMonth, $theYear, 'LIVING COST ALLOWANCES', $row, $y['tbh'], '');
                    $this->setBatchDetail($theMonth, $theYear, 'TUNJANGAN REKOMPOSISI', $row, $y['rekomposisi'], '');

                }
            }

            $this->setElement($theMonth, $theYear, 'BASE SALARIES', $data[$i]['TOTAL GAJI DASAR']);
            $this->setElement($theMonth, $theYear, 'FUNCTIONAL ALLOWANCES', $data[$i]['TUNJANGAN JABATAN']);
            $this->setElement($theMonth, $theYear, 'LIVING COST ALLOWANCES', $data[$i]['TOTAL TBH']);
            $this->setElement($theMonth, $theYear, 'EMPLOYEES INCOME TAX', $data[$i]['EMPLOYEE INCOME TAX']);
            $this->setElement($theMonth, $theYear, 'EMPLOYEES BPJS', $data[$i]['BPJS KESEHATAN'] + $data[$i]['BPJS KETENEGAKERJAAN'] + (empty($data[$i]['TOTAL KENAIKAN']) ? 0 : $data[$i]['TOTAL KENAIKAN']));

            //$this->setElement($theMonth, $theYear, 'BPJS KESEHATAN', $data[$i]['BPJS KESEHATAN'] + (empty($data[$i]['TOTAL KENAIKAN']) ? 0 : $data[$i]['TOTAL KENAIKAN']));
            //$this->setElement($theMonth, $theYear, 'BPJS KETENEGAKERJAAN', $data[$i]['BPJS KETENEGAKERJAAN'] + (empty($data[$i]['TOTAL KENAIKAN']) ? 0 : $data[$i]['TOTAL KENAIKAN']));

            $this->setElement($theMonth, $theYear, 'TUNJANGAN REKOMPOSISI', $data[$i]['TOTAL REKOMPOSISI']);

            $this->setElement($theMonth, $theYear, 'PENGHARGAAN MASA KERJA', $data[$i]['PMK']);
            $this->setElement($theMonth, $theYear, 'CUTI BESAR', $data[$i]['CUTI BESAR']);


        }


        var_dump($data, $element, $nik);

        return $data;
    }


    public function setSimulationTest()
    {

        //get value from setting model
        $indexTHR = floatval(Setting::getBaseSetting(Setting::INDEX_THR_1));
        $indexTHR2 = floatval(Setting::getBaseSetting(Setting::INDEX_THR_2));
        $indexTA = floatval(Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_AKHIR_TAHUN));
        $indexUangSakuAP = floatval(Setting::getBaseSetting(Setting::INDEX_UANG_SAKU_AKHIR_PROGRAM));

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

        //IE
        $indexIE1 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_1));
        $indexIE2 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_2));
        $indexIE3 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_3));
        $indexIE4 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_4));
        $indexIE5 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_5));
        $indexIE6 = floatval(Setting::getBaseSetting(Setting::INDEX_IE_6));
        $indexIEContract = floatval(Setting::getBaseSetting(Setting::INDEX_IE_CONTRACT));
        $indexIETelkom = floatval(Setting::getBaseSetting(Setting::INDEX_IE_TELKOM));

        //INSENTIF SEMESTERAN
        $indexISTelkom = floatval(Setting::getBaseSetting(Setting::INDEX_IS_TELKOM));
        $indexISContractProf = floatval(Setting::getBaseSetting(Setting::INDEX_IS_CONTRACT_PROF));

        $nikYangDimaksud = $this->nik;

        // ambil semua nik unik
        $niks = Employee::find()->select('nik')
            ->where(['status' => Employee::ACTIVE])
            ->andWhere(['not', ['salary' => NULL]])
            ->andWhere(['nik' => $nikYangDimaksud])
            ->andFilterWhere(['not', ['tunjangan' => NULL]])
            ->andFilterWhere(['not', ['tunjangan_rekomposisi' => NULL]])
            ->distinct()
            ->all();


        // ambil semua career path employee
        $data = [];

        $dates = Helpers::getMonthIterator($this->start_date, $this->end_date);

        //array tahun
        $element = [];

        // cache tabel gaji
        $gajiTable = MstGaji::find()->asArray()->all();

        //ambil kota
        $theCity = MstCity::find()->asArray()->all();

        foreach ($niks as $theNik) {
            // get employee
            $theEmployee = Employee::find()
                ->where(['status' => Employee::ACTIVE])
                ->andWhere(['nik' => $theNik->nik])
                ->orderBy(['start_date_assignment' => SORT_DESC])
                ->one();

            $careerPath = $theEmployee->getCareerPath($theEmployee->start_date_assignment, $this->end_date);

            if ($theEmployee->employee_category == 'PROBATION') {
                $maxGaji = $theEmployee->salary * 0.9;
                $maxTbh = $theEmployee->tunjangan * 0.9;
                $maxRekomposisi = $theEmployee->tunjangan_rekomposisi * 0.9;
                $maxTunjab = $theEmployee->tunjangan_jabatan * 0.9;
            } else {
                $maxGaji = $theEmployee->salary;
                $maxTbh = $theEmployee->tunjangan;
                $maxRekomposisi = $theEmployee->tunjangan_rekomposisi;
                $maxTunjab = $theEmployee->tunjangan_jabatan;
            }

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

                $data[$dateFmt]['selisih_gaji_dasar'] += 0;
                $data[$dateFmt]['selisih_tbh'] += 0;
                $data[$dateFmt]['selisih_rekomposisi'] += 0;
                $data[$dateFmt]['tunjangan_jabatan'] += 0;

                $data[$dateFmt]['jumlah_newbi'] += 0; // catat jumlah employee yg naik BI nya

                //hitung masa kerja karyawan
                $currentYear = $date->format('Y-m');
                $diff = (new DateTime($tahunMasuk))->diff(new DateTime($currentYear));
                $masaKerja = $diff->m + $diff->y * 12;


                // ambil gaji
                if (empty($careerPath['path'])) {
                    // bi atau bp nya null --> contract
                    $gaji = $theEmployee->salary;
                    $tbh = $theEmployee->tunjangan;
                    $rekomposisi = $theEmployee->tunjangan_rekomposisi;
                    $tunjab = $theEmployee->tunjangan_jabatan;
                } elseif ($theEmployee->bi == $careerPath['path'][$dateFmt]['bi']) {
                    if ($theEmployee->employee_category == 'PROBATION') {
                        $gaji = $theEmployee->salary * 0.9;
                        $tbh = $theEmployee->tunjangan * 0.9;
                        $rekomposisi = $theEmployee->tunjangan_rekomposisi * 0.9;
                        $tunjab = $theEmployee->tunjangan_jabatan * 0.9;
                    } else {
                        $gaji = $theEmployee->salary;
                        $tbh = $theEmployee->tunjangan;
                        $rekomposisi = $theEmployee->tunjangan_rekomposisi;
                        $tunjab = $theEmployee->tunjangan_jabatan;
                    }
                } else {

                    $oldBi = $careerPath['path'][$prevDateFmt]['bi'];
                    if ($oldBi == $theEmployee->bi) {
                        $oldGaji = $theEmployee->salary;
                        $oldTbh = $theEmployee->tunjangan;
                        $oldRekomposisi = $theEmployee->tunjangan_rekomposisi;
                        $oldTunjab = $theEmployee->tunjangan_jabatan;
                    } else {

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

                    if ($theEmployee->employee_category == 'PROBATION') {
                        $key = array_search($theEmployee->bi, array_column($gajiTable, 'bi'));
                    } else {
                        $key = array_search($careerPath['path'][$dateFmt]['bi'], array_column($gajiTable, 'bi'));
                    }
                    $theGaji = $gajiTable[$key];

                    $keyCity = array_search($theEmployee->kode_kota, array_column($theCity, 'code'));
                    $city = $theCity[$keyCity];

                    $gaji = $theGaji['gaji_dasar'];
                    $tbh = empty($city['idx_tbh']) ? $theGaji['tunjangan_biaya_hidup'] : $theGaji['tunjangan_biaya_hidup'] * $city['idx_tbh'];
                    $rekomposisi = $theGaji['tunjangan_rekomposisi'];

                    if ($theEmployee->structural) {
                        $tunjab = $theGaji['tunjangan_jabatan_struktural'];
                    } else {
                        $tunjab = $theGaji['tunjangan_jabatan_functional'];
                    }


                }


                if ($careerPath['path'][$dateFmt]['is_naik_bi']) {

                    if ($prevGajiDasar > $gaji) {
                        echo "<br>\n===========\nnew Gaji Dasar is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevGajiDasar) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($gaji) . " (from mst_gaji table) \n<br>";
                        $gaji = $prevGajiDasar;
                    }

                    if ($maxGaji > $gaji) {
                        $gaji = $maxGaji;
                    }

                    if ($prevTbh > $tbh) {
                        echo "\n===========\nnew TBH is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTbh) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($tbh) . " (from mst_gaji table) \n<br>";
                        $tbh = $prevTbh;
                    }

                    if ($maxTbh > $tbh) {
                        $tbh = $maxTbh;
                    }

                    if ($prevRekomposisi > $rekomposisi) {
                        echo "\n===========\nnew REkomposisi is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevRekomposisi) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($rekomposisi) . " (from mst_gaji table) \n<br>";
                        $rekomposisi = $prevRekomposisi;
                    }

                    if ($maxRekomposisi > $rekomposisi) {
                        $rekomposisi = $maxRekomposisi;
                    }

                    if ($prevTunjab > $tunjab) {
                        echo "\n===========\nnew Tunjangan Jabatan is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTunjab) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($tunjab) . " (from mst_gaji table) \n<br>";
                        $tunjab = $prevTunjab;
                    }

                    if ($maxTunjab > $tunjab) {
                        $tunjab = $maxTunjab;
                    }

                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['jumlah_newbi_band_1'] += intval($careerPath['path'][$dateFmt]['new_bi_band_1']);
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['jumlah_newbi_band_others'] += intval($careerPath['path'][$dateFmt]['new_bi_band_others']);

                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['gaji dasar'] += $gaji - $prevGajiDasar;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tbh'] += $tbh - $prevTbh;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['rekomposisi'] += $rekomposisi - $prevRekomposisi;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tunjangan jabatan'] += $tunjab - $prevTunjab;

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

                //total gaji tiap orang per bulan&tahun simulation
                if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['TOTAL GAJI INDIVIDU'] = $gaji + $tbh;
                } else {
                    $data[$dateFmt]['TOTAL GAJI INDIVIDU'] = $gaji + $tbh + $rekomposisi;
                }
                $data[$dateFmt]['GAJI DASAR'] += $gaji;
                $data[$dateFmt]['TBH'] += $tbh;
                $data[$dateFmt]['REKOMPOSISI'] += $rekomposisi;
                $data[$dateFmt]['TUNJANGAN JABATAN'] += $tunjab;

                if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
                    $data[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($this->perc_inc_gadas) ? 0 : $data[$dateFmt]['GAJI DASAR'] * $this->perc_inc_gadas / 100);
                    $data[$dateFmt]['KENAIKAN TBH'] =
                        (empty($this->perc_inc_tbh) ? 0 : $data[$dateFmt]['TBH'] * $this->perc_inc_tbh / 100);
                    $data[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($this->perc_inc_rekomposisi) ? 0 : $data[$dateFmt]['REKOMPOSISI'] * $this->perc_inc_rekomposisi / 100);
                    $data[$dateFmt]['TOTAL KENAIKAN'] = $data[$dateFmt]['KENAIKAN GADAS'] + $data[$dateFmt]['KENAIKAN TBH'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];
                }

                $data[$dateFmt]['TOTAL GAJI DASAR'] = $data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['KENAIKAN GADAS'];
                $data[$dateFmt]['TOTAL TBH'] = $data[$dateFmt]['TBH'] + $data[$dateFmt]['KENAIKAN TBH'];
                $data[$dateFmt]['TOTAL REKOMPOSISI'] = $data[$dateFmt]['REKOMPOSISI'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];

                $data[$dateFmt]['TOTAL'] = ($data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['TBH'] + $data[$dateFmt]['REKOMPOSISI']) + (empty($data[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $data[$dateFmt]['TOTAL KENAIKAN']);


                //element bpjs ketenagakerjaan
                //JHT
                if ($theEmployee->employee_category !== 'TRAINEE') {
                    $totalJHT = $iuranJHT * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                //JKEM
                if ($theEmployee->employee_category == 'TRAINEE') {
                    $totalJKM = $iuranJKM * $data[$dateFmt]['TOTAL GAJI DASAR'];
                } else {
                    $totalJKM = $iuranJKM * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                //JKK
                if ($theEmployee->employee_category == 'TRAINEE') {
                    $totalJKK = $iuranJKK * $data[$dateFmt]['TOTAL GAJI DASAR'];
                } else {
                    $totalJKK = $iuranJKM * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                //JP
                if ($theEmployee->employee_category !== 'TRAINEE') {
                    //validate max upah untuk Iuran JP
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                        $totalJP = $iuranJP * floatval($maxJP);
                    } else {
                        $totalJP = $iuranJP * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                }


                //element bpjs kesehatan
                if ($theEmployee->employee_category !== 'TRAINEE') {
                    //validate max upah untuk Iuran JKes
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJkes) {
                        $totalKes = $iuranKes * floatval($maxJkes);
                    } else {
                        $totalKes = $iuranKes * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                }


                $data[$dateFmt]['BPJS KETENEGAKERJAAN'] += ($totalJHT + $totalJP + $totalJKK + $totalJKM);
                $data[$dateFmt]['BPJS KESEHATAN'] += $totalKes;

                //$data[$dateFmt]['EMPLOYEE INCOME TAX'] = $tax * $data[$dateFmt]['TOTAL'];

                //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                $data[$dateFmt]['EMPLOYEE INCOME TAX'] = (($data[$dateFmt]['TOTAL'] + $data[$dateFmt]['TUNJANGAN JABATAN']) / (1 - $tax)) * $tax;


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
                    } else {
                        echo "";
                    }
                }

                if ($theEmployee->employee_category == 'PERMANENT') {
                    //CUTI BESAR => 6 TAHUN = 72 BULAN
                    if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                        $data[$dateFmt]['CUTI BESAR'] = $indexCutiBesar * $data[$dateFmt]['TOTAL'];
                    } else {
                        echo "";
                    }
                }
            }


            $start = current(array_keys($data));
            $end = end(array_keys($data));

            $theFirstYear = intval(substr($start, 0, 4));
            $theLastYear = intval(substr($end, 0, 4));

            $theFirstMonth = 12;
            $theFirstMonths = intval(substr($start, 4, 2));
            $theMonths = substr($end, 4, 2);
            $theLastMonth = intval(substr($end, 4, 2));


            for ($i = $theFirstYear; $i <= $theLastYear; $i++) {

                $currentSemester = ceil(intval($date->format('m')) / 6);  // bulan ini semester berapa?
                $currentYear = intval($date->format('Y'));


                if ($i < $theLastYear) {

                    //Insentif Semesteran
                    $band = intval(substr($careerPath['path'][$i . $theFirstMonth]['bi'], 0, 1));

                    $theInsentif = Insentif::find()->where([
                        'nik' => $theNik->nik,
                        'tahun' => $date->format('Y'),
                    ])
                        ->andWhere(['smt' => $currentSemester])
                        ->andWhere(['tahun' => $currentYear])
                        ->orderBy(['smt' => SORT_DESC])
                        ->one();

                    $nkk = Insentif::getConvertionNkk(floatval($theInsentif->nkk));
                    $nku = Insentif::getConvertionNku(floatval($theInsentif->nku));
                    $nki = Insentif::getConvertionNki(floatval($theInsentif->nki));


                    //echo "pakai". $theFirstMonth."\n";
                    $element[$i . $theFirstMonth]['LAST TOTAL'] = $data[$i . $theFirstMonth]['GAJI DASAR'] + $data[$i . $theFirstMonth]['TBH'] + $data[$i . $theFirstMonth]['REKOMPOSISI'] + (empty($data[$i . $theFirstMonth]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theFirstMonth]['TOTAL KENAIKAN']);

                    //THR
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR2 * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * ($element[$i . $theFirstMonth]['LAST TOTAL'] - $data[$i . $theFirstMonth]['REKOMPOSISI'])) / $theFirstMonth;
                    } else if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $data[$i . $theFirstMonth]['GAJI DASAR']) / $theFirstMonth;
                    } else {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    }

                    //CUTI TAHUNAN
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * ($element[$i . $theFirstMonth]['LAST TOTAL'] - $data[$i . $theFirstMonth]['REKOMPOSISI'])) / $theFirstMonth;
                    }

                    //TUNJANGAN AKHIR TAHUN
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theFirstMonth]['TUNJANGAN AKHIR TAHUN'] = ($indexTA * ($element[$i . $theFirstMonth]['LAST TOTAL'] - $data[$i . $theFirstMonth]['REKOMPOSISI'])) / $theFirstMonth;
                    }

                    //UANG SAKU AKHIR PROGRAM
                    if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theFirstMonth]['UANG SAKU AKHIR PROGRAM'] = ($indexUangSakuAP * $data[$i . $theFirstMonth]['GAJI DASAR']) / $theFirstMonth;
                    }

                    //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                    $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX'] = (($element[$i . $theFirstMonth]['THR'] + $element[$i . $theFirstMonth]['CUTI TAHUNAN']) / (1 - $tax)) * $tax;

                    if ($theInsentif) {
                        if ($theEmployee->employee_category != 'TRAINEE') {
                            //konstanta * nkk * nku * nki * TOTAL
                            if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                                if ($band == 1 || $band ==2 || $band ==3) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 2.830 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                } else if ($band == 4) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 3.530 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                } else if ($band == 5) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 4.950 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                } else if ($band == 6) {
                                    $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 7.800 * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                                }
                            } //konstanta * nki * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT') {
                                $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += 4.000 * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT PROF' && $theEmployee->employee_category == 'EXPATRIATE') {
                                $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += $indexISContractProf * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'TELKOM') {
                                $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] += $indexISTelkom * $nkk * $nku * $nki * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            }
                        }
                    }

                    //Insentif Ekstra
                    if ($theEmployee->employee_category != 'TRAINEE' || $theEmployee->employee_category != 'CONTRACT PROF' || $theEmployee->employee_category != 'EXPATRIATE') {
                        //konstanta * TOTAL
                        if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                            if ($band == 1) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE1 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 2) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE2 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 3) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE3 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 4) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE4 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 5) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE5 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            } else if ($band == 6) {
                                $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIE6 * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                            }
                        } //konstanta * TOTAL
                        else if ($theEmployee->employee_category == 'CONTRACT') {
                            $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIEContract * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;
                        } //konstanta(FREE INPUT) * TOTAL
                        else if ($theEmployee->employee_category == 'TELKOM') {
                            $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] += $indexIETelkom * $data[$i . $theFirstMonth]['TOTAL GAJI INDIVIDU'] / $theFirstMonth;                        }
                    }

                } else {

                    //echo "pakai". $theLastMonth."\n";

                    //Insentif Semesteran
                    $band = intval(substr($careerPath['path'][$i . $theMonths]['bi'], 0, 1));

                    $theInsentif = Insentif::find()->where([
                        'nik' => $theNik->nik,
                        'tahun' => $date->format('Y'),
                    ])
                        ->andWhere(['smt' => $currentSemester])
                        ->andWhere(['tahun' => $currentYear])
                        ->orderBy(['smt' => SORT_DESC])
                        ->one();


                    $nkk = Insentif::getConvertionNkk(floatval($theInsentif->nkk));
                    $nku = Insentif::getConvertionNku(floatval($theInsentif->nku));
                    $nki = Insentif::getConvertionNki(floatval($theInsentif->nki));


                    $element[$i . $theMonths]['LAST TOTAL'] = $data[$i . $theMonths]['GAJI DASAR'] + $data[$i . $theMonths]['TBH'] + $data[$i . $theMonths]['REKOMPOSISI'] + (empty($data[$i . $theMonths]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theMonths]['TOTAL KENAIKAN']);


                    //THR
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR2 * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * ($element[$i . $theMonths]['LAST TOTAL'] - $data[$i . $theMonths]['REKOMPOSISI'])) / $theLastMonth) / (12 / $theLastMonth);
                    } else if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * $data[$i . $theMonths]['GAJI DASAR']) / $theLastMonth) / (12 / $theLastMonth);
                    } else {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    }

                    //CUTI TAHUNAN
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    } else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * ($element[$i . $theMonths]['LAST TOTAL'] - $data[$i . $theMonths]['REKOMPOSISI'])) / $theLastMonth) / (12 / $theLastMonth);
                    }

                    //TUNJANGAN AKHIR TAHUN
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theMonths]['TUNJANGAN AKHIR TAHUN'] = (($indexTA * ($element[$i . $theMonths]['LAST TOTAL'] - $data[$i . $theMonths]['REKOMPOSISI'])) / $theLastMonth) / (12 / $theLastMonth);
                    }

                    //UANG SAKU AKHIR PROGRAM
                    if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theMonths]['UANG SAKU AKHIR PROGRAM'] = (($indexUangSakuAP * $data[$i . $theMonths]['GAJI DASAR']) / $theLastMonth) / (12 / $theLastMonth);
                    }


                    //((GD+OVERTIME+PERF.INSENTIVE+TUNJAB+TBH+THR+CUTI TAHUNAN+TA+REKOMPOSISI+RELOCATION)/(1-$tax)) * $tax
                    $element[$i . $theMonths]['EMPLOYEE INCOME TAX'] = (($element[$i . $theMonths]['THR'] + $element[$i . $theMonths]['CUTI TAHUNAN']) / (1 - $tax)) * $tax;


                    if ($theInsentif) {
                        if ($theEmployee->employee_category != 'TRAINEE') {
                            //konstanta * nkk * nku * nki * TOTAL
                            if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                                if ($band == 1 || $band ==2 || $band ==3) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 2.830 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                } else if ($band == 4) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 3.530 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                } else if ($band == 5) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 4.950 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                } else if ($band == 6) {
                                    $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 7.800 * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                                }
                            } //konstanta * nki * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT') {
                                $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += 4.000 * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU']  / (($theLastMonth) / (12 / $theLastMonth));
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'CONTRACT PROF' && $theEmployee->employee_category == 'EXPATRIATE') {
                                $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += $indexISContractProf * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU']  / (($theLastMonth) / (12 / $theLastMonth));
                            } //konstanta(FREE INPUT) * TOTAL
                            else if ($theEmployee->employee_category == 'TELKOM') {
                                $element[$i . $theMonths]['INSENTIF SEMESTERAN'] += $indexISTelkom * $nkk * $nku * $nki * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU']  / (($theLastMonth) / (12 / $theLastMonth));
                            }
                        }

                    }

                    //Insentif Ekstra
                    if ($theEmployee->employee_category != 'TRAINEE' || $theEmployee->employee_category != 'CONTRACT PROF' || $theEmployee->employee_category != 'EXPATRIATE') {
                        //konstanta * TOTAL
                        if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                            if ($band == 1) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE1 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 2) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE2 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 3) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE3 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 4) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE4 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 5) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE5 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            } else if ($band == 6) {
                                $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIE6 * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                            }
                        } //konstanta * TOTAL
                        else if ($theEmployee->employee_category == 'CONTRACT') {
                            $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIEContract * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                        } //konstanta(FREE INPUT) * TOTAL
                        else if ($theEmployee->employee_category == 'TELKOM') {
                            $element[$i . $theMonths]['INSENTIF EKSTRA'] += $indexIETelkom * $data[$i . $theMonths]['TOTAL GAJI INDIVIDU'] / (($theLastMonth) / (12 / $theLastMonth));
                        }
                    }

                }

            }

        }

        for ($i = $theFirstYear; $i <= $theLastYear; $i++) {
            if ($i < $theLastYear) {
                for ($y = $theFirstMonths; $y <= $theFirstMonth; $y++) {

                    echo "<br/>\n===========Employees Income Tax===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theFirstYear . "\n<br/>";
                    echo "Nilai :" . $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX'] . "\n<br/><br/>";

                    echo "<br/>\n===========OTHER ALLOWANCE===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theFirstYear . "\n<br/>";
                    $total = $element[$i . $theFirstMonth]['CUTI TAHUNAN'] + $element[$i . $theFirstMonth]['THR'];
                    echo "Nilai :" . $total . "\n<br/><br/>";

                    echo "<br/>\n===========TUNJANGAN AKHIR TAHUN===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theFirstYear . "\n<br/>";
                    $total = $element[$i . $theFirstMonth]['TUNJANGAN AKHIR TAHUN'];
                    echo "Nilai :" . $total . "\n<br/><br/>";

                    echo "<br/>\n===========UANG SAKU AKHIR PROGRAM===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theFirstYear . "\n<br/>";
                    $total = $element[$i . $theFirstMonth]['UANG SAKU AKHIR PROGRAM'];
                    echo "Nilai :" . $total . "\n<br/><br/>";

                    echo "<br/>\n===========PERFORMANCE INCENTIVE===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theFirstYear . "\n<br/>";
                    $total = $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] + $element[$i . $theFirstMonth]['INSENTIF EKSTRA'];
                    echo "Nilai Insentif Semesteran:" . $element[$i . $theFirstMonth]['INSENTIF SEMESTERAN'] . "\n<br/>";
                    echo "Nilai Insentif Ekstra:" . $element[$i . $theFirstMonth]['INSENTIF EKSTRA'] . "\n<br/>";
                    echo "Nilai Performance Insentif:" . $total . "\n<br/><br/>";

//                    echo "<br/>\n===========THR===========\n<br/>";
//                    echo "Bulan :" . $y . "\n<br/>";
//                    echo "Tahun :" . $theFirstYear . "\n<br/>";
//                    echo "Nilai :" . $element[$i . $theFirstMonth]['THR'] . "\n<br/><br/>";
//
//                    echo "<br/>\n===========CUTI TAHUNAN===========\n<br/>";
//                    echo "Bulan :" . $y . "\n<br/>";
//                    echo "Tahun :" . $theFirstYear . "\n<br/>";
//                    echo "Nilai :" . $element[$i . $theFirstMonth]['CUTI TAHUNAN'] . "\n<br/><br/>";

                }
            } else {
                for ($y = $theFirstMonths; $y <= $theLastMonth; $y++) {

                    echo "<br/>\n===========Employees Income Tax===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theLastYear . "\n<br/>";
                    echo "Nilai :" . $element[$i . $theMonths]['EMPLOYEE INCOME TAX'] . "\n<br/><br/>";

                    echo "<br/>\n===========OTHER ALLOWANCE===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theLastYear . "\n<br/>";
                    $total = $element[$i . $theMonths]['CUTI TAHUNAN'] + $element[$i . $theMonths]['THR'];
                    echo "Nilai :" . $total . "\n<br/><br/>";

                    echo "<br/>\n===========TUNJANGAN AKHIR TAHUN===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theLastYear . "\n<br/>";
                    $total = $element[$i . $theMonths]['TUNJANGAN AKHIR TAHUN'];
                    echo "Nilai :" . $total . "\n<br/><br/>";

                    echo "<br/>\n===========UANG SAKU AKHIR PROGRAM===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theLastYear . "\n<br/>";
                    $total = $element[$i . $theMonths]['UANG SAKU AKHIR PROGRAM'];
                    echo "Nilai :" . $total . "\n<br/><br/>";

                    echo "<br/>\n===========PERFORMANCE INCENTIVE===========\n<br/>";
                    echo "Bulan :" . $y . "\n<br/>";
                    echo "Tahun :" . $theFirstYear . "\n<br/>";
                    $total = $element[$i . $theMonths]['INSENTIF SEMESTERAN'] + $element[$i . $theMonths]['INSENTIF EKSTRA'];
                    echo "Nilai Insentif Semesteran:" . $element[$i . $theMonths]['INSENTIF SEMESTERAN'] . "\n<br/>";
                    echo "Nilai Insentif Ekstra:" . $element[$i . $theMonths]['INSENTIF EKSTRA'] . "\n<br/>";
                    echo "Nilai Performance Insentif:" . $total . "\n<br/><br/>";

//                    echo "<br/>\n===========THR===========\n<br/>";
//                    echo "Bulan :" . $y . "\n<br/>";
//                    echo "Tahun :" . $theLastYear . "\n<br/>";
//                    echo "Nilai :" . $element[$i . $theMonths]['THR'] . "\n<br/><br/>";
//
//                    echo "<br/>\n===========CUTI TAHUNAN===========\n<br/>";
//                    echo "Bulan :" . $y . "\n<br/>";
//                    echo "Tahun :" . $theLastYear . "\n<br/>";
//                    echo "Nilai :" . $element[$i . $theMonths]['CUTI TAHUNAN'] . "\n<br/><br/>";

                }
            }
        }


        foreach ($data as $i => $rows) {
            $theMonth = substr($i, 4, 2);
            $theYear = substr($i, 0, 4);

            if (!empty($data[$i]['SELISIH'])) {
                foreach ($data[$i]['SELISIH'] as $row => $y) {
                    if ($row == 'EVALUASI') {

                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "JUMLAH NEW BI :" . $y['jumlah_newbi_band_others'] . "\n<br/>";
                    } else {

                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "JUMLAH NEW BI :" . $y['jumlah_newbi_band_1'] . "\n<br/>";
                    }


                    echo "<br/>\n===========$row===========\n<br/>";
                    echo "Bulan :" . $theMonth . "\n<br/>";
                    echo "Tahun :" . $theYear . "\n<br/>";
                    echo "Element : GAJI DASAR " . "\n<br/>";
                    echo "Nilai :" . $y['gaji dasar'] . "\n<br/>";

                    echo "<br/>\n===========$row===========\n<br/>";
                    echo "Bulan :" . $theMonth . "\n<br/>";
                    echo "Tahun :" . $theYear . "\n<br/>";
                    echo "Element : TBH " . "\n<br/>";
                    echo "Nilai :" . $y['tbh'] . "\n<br/>";

                    echo "<br/>\n===========$row===========\n<br/>";
                    echo "Bulan :" . $theMonth . "\n<br/>";
                    echo "Tahun :" . $theYear . "\n<br/>";
                    echo "Element : REKOMPOSISI " . "\n<br/>";
                    echo "Nilai :" . $y['rekomposisi'] . "\n<br/>";

                    echo "<br/>\n===========$row===========\n<br/>";
                    echo "Bulan :" . $theMonth . "\n<br/>";
                    echo "Tahun :" . $theYear . "\n<br/>";
                    echo "Element : TUNJANGAN JABATAN " . "\n<br/>";
                    echo "Nilai :" . $y['tunjangan jabatan'] . "\n<br/>";
                }
            }

            echo "<br/>\n===========Gaji Dasar===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['GAJI DASAR'] . "\n<br/>";

            echo "<br/>\n===========Kenaikan Gaji Dasar===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['KENAIKAN GADAS'] . "\n<br/><br/>";

            echo "<br/>\n===========Total Gaji Dasar===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TOTAL GAJI DASAR'] . "\n<br/><br/>";

            echo "\n===========TBH===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TBH'] . "\n<br/><br/>";

            echo "<br/>\n===========Kenaikan TBH===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['KENAIKAN TBH'] . "\n<br/><br/>";

            echo "<br/>\n===========Total TBH===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TOTAL TBH'] . "\n<br/><br/>";


            echo "\n===========TUNJANGAN REKOMPOSISI===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['REKOMPOSISI'] . "\n<br/><br/>";

            echo "<br/>\n===========Kenaikan REKOMPOSISI===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['KENAIKAN REKOMPOSISI'] . "\n<br/><br/>";

            echo "<br/>\n===========Total REKOMPOSISI===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TOTAL REKOMPOSISI'] . "\n<br/><br/>";

            echo "\n===========TUNJANGAN JABATAN===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TUNJANGAN JABATAN'] . "\n<br/><br/>";

            echo "<br/>\n===========CUTI BESAR===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['CUTI BESAR'] . "\n<br/><br/>";

            echo "<br/>\n===========PMK===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['PMK'] . "\n<br/><br/>";

            echo "<br/>\n===========BPJS KESEHATAN===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Total Gaji :" . $data[$i]['TOTAL GAJI INDIVIDU'] . "\n<br/>";
            $BPJSKES = $data[$i]['BPJS KESEHATAN'] + (empty($data[$i]['TOTAL KENAIKAN']) ? 0 : $data[$i]['TOTAL KENAIKAN']);
            echo "Nilai BPJS Kesehatan:" . $BPJSKES . "\n<br/><br/>";


            echo "<br/>\n===========BPJS KETENEGAKERJAAN===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Total Gaji :" . $data[$i]['TOTAL GAJI INDIVIDU'] . "\n<br/>";
            $BPJSKET = $data[$i]['BPJS KETENEGAKERJAAN'] + (empty($data[$i]['TOTAL KENAIKAN']) ? 0 : $data[$i]['TOTAL KENAIKAN']);
            echo "Nilai BPJS Ketenagakerjaan:" . $BPJSKET . "\n<br/><br/>";

            echo "<br/>\n===========Employee BPJS ===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            $total = $BPJSKES + $BPJSKET;
            echo "Nilai Employee BPJS :" . $total . "\n<br/><br/>";

            echo "<br/>\n===========EMPLOYEE INCOME TAX===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['EMPLOYEE INCOME TAX'] . "\n<br/><br/>";



        }

        //var_dump($data, $element);

        return $data;
    }

    public function setSimulationElementTest($elementss)
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
        $maxJP = Setting::getMaxJP();
        $maxJkes = Setting::getMaxJKes();

        //value PMK Structure
        $indexPMK5 = Setting::getindexPMK5();
        $indexPMK10 = Setting::getindexPMK10();
        $indexPMK15 = Setting::getindexPMK15();
        $indexPMK20 = Setting::getindexPMK20();
        $indexPMK25 = Setting::getindexPMK25();
        $indexPMK30 = Setting::getindexPMK30();


        $nikYangDimaksud = $this->nik;

        // ambil semua nik unik
        $niks = Employee::find()->select('nik')
            ->where(['status' => Employee::ACTIVE])
            ->andWhere(['not', ['salary' => NULL]])
            ->andWhere(['nik' => $nikYangDimaksud])
            ->andFilterWhere(['not', ['tunjangan' => NULL]])
            ->andFilterWhere(['not', ['tunjangan_rekomposisi' => NULL]])
            ->distinct()
            ->all();


        // ambil semua career path employee
        $data = [];

        $dates = Helpers::getMonthIterator($this->start_date, $this->end_date);

        //array tahun
        $element = [];

        // cache tabel gaji
        $gajiTable = MstGaji::find()->asArray()->all();

        //ambil kota
        $theCity = MstCity::find()->asArray()->all();

        foreach ($niks as $theNik) {
            // get employee
            $theEmployee = Employee::find()
                ->where(['status' => Employee::ACTIVE])
                ->andWhere(['nik' => $theNik->nik])
                ->orderBy(['start_date_assignment' => SORT_DESC])
                ->one();

            $careerPath = $theEmployee->getCareerPath($theEmployee->start_date_assignment, $this->end_date);

            if ($theEmployee->employee_category == 'PROBATION') {
                $maxGaji = $theEmployee->salary * 0.9;
                $maxTbh = $theEmployee->tunjangan * 0.9;
                $maxRekomposisi = $theEmployee->tunjangan_rekomposisi * 0.9;
                $maxTunjab = $theEmployee->tunjangan_jabatan * 0.9;
            } else {
                $maxGaji = $theEmployee->salary;
                $maxTbh = $theEmployee->tunjangan;
                $maxRekomposisi = $theEmployee->tunjangan_rekomposisi;
                $maxTunjab = $theEmployee->tunjangan_jabatan;
            }

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

                $data[$dateFmt]['selisih_gaji_dasar'] += 0;
                $data[$dateFmt]['selisih_tbh'] += 0;
                $data[$dateFmt]['selisih_rekomposisi'] += 0;
                $data[$dateFmt]['tunjangan_jabatan'] += 0;

                $data[$dateFmt]['jumlah_newbi'] += 0; // catat jumlah employee yg naik BI nya

                //hitung masa kerja karyawan
                $currentYear = $date->format('Y-m');
                $diff = (new DateTime($tahunMasuk))->diff(new DateTime($currentYear));
                $masaKerja = $diff->m + $diff->y * 12;


                // ambil gaji
                if (empty($careerPath['path'])) {
                    // bi atau bp nya null --> contract
                    $gaji = $theEmployee->salary;
                    $tbh = $theEmployee->tunjangan;
                    $rekomposisi = $theEmployee->tunjangan_rekomposisi;
                    $tunjab = $theEmployee->tunjangan_jabatan;
                } elseif ($theEmployee->bi == $careerPath['path'][$dateFmt]['bi']) {
                    if ($theEmployee->employee_category == 'PROBATION') {
                        $gaji = $theEmployee->salary * 0.9;
                        $tbh = $theEmployee->tunjangan * 0.9;
                        $rekomposisi = $theEmployee->tunjangan_rekomposisi * 0.9;
                        $tunjab = $theEmployee->tunjangan_jabatan * 0.9;
                    } else {
                        $gaji = $theEmployee->salary;
                        $tbh = $theEmployee->tunjangan;
                        $rekomposisi = $theEmployee->tunjangan_rekomposisi;
                        $tunjab = $theEmployee->tunjangan_jabatan;
                    }
                } else {

                    $oldBi = $careerPath['path'][$prevDateFmt]['bi'];
                    if ($oldBi == $theEmployee->bi) {
                        $oldGaji = $theEmployee->salary;
                        $oldTbh = $theEmployee->tunjangan;
                        $oldRekomposisi = $theEmployee->tunjangan_rekomposisi;
                        $oldTunjab = $theEmployee->tunjangan_jabatan;
                    } else {

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

                    if ($theEmployee->employee_category == 'PROBATION') {
                        $key = array_search($theEmployee->bi, array_column($gajiTable, 'bi'));
                    } else {
                        $key = array_search($careerPath['path'][$dateFmt]['bi'], array_column($gajiTable, 'bi'));
                    }
                    $theGaji = $gajiTable[$key];

                    $keyCity = array_search($theEmployee->kode_kota, array_column($theCity, 'code'));
                    $city = $theCity[$keyCity];

                    $gaji = $theGaji['gaji_dasar'];
                    $tbh = empty($city['idx_tbh']) ? $theGaji['tunjangan_biaya_hidup'] : $theGaji['tunjangan_biaya_hidup'] * $city['idx_tbh'];
                    $rekomposisi = $theGaji['tunjangan_rekomposisi'];

                    if ($theEmployee->structural) {
                        $tunjab = $theGaji['tunjangan_jabatan_struktural'];
                    } else {
                        $tunjab = $theGaji['tunjangan_jabatan_functional'];
                    }


                }


                if ($careerPath['path'][$dateFmt]['is_naik_bi']) {

                    if ($prevGajiDasar > $gaji) {
                        echo "<br>\n===========\nnew Gaji Dasar is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevGajiDasar) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($gaji) . " (from mst_gaji table) \n<br>";
                        $gaji = $prevGajiDasar;
                    }

                    if ($maxGaji > $gaji) {
                        $gaji = $maxGaji;
                    }

                    if ($prevTbh > $tbh) {
                        echo "\n===========\nnew TBH is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTbh) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($tbh) . " (from mst_gaji table) \n<br>";
                        $tbh = $prevTbh;
                    }

                    if ($maxTbh > $tbh) {
                        $tbh = $maxTbh;
                    }

                    if ($prevRekomposisi > $rekomposisi) {
                        echo "\n===========\nnew REkomposisi is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevRekomposisi) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($rekomposisi) . " (from mst_gaji table) \n<br>";
                        $rekomposisi = $prevRekomposisi;
                    }

                    if ($maxRekomposisi > $rekomposisi) {
                        $rekomposisi = $maxRekomposisi;
                    }

                    if ($prevTunjab > $tunjab) {
                        echo "\n===========\nnew Tunjangan Jabatan is insane!\n";
                        echo $oldBi . ' to ' . $careerPath['path'][$dateFmt]['bi'] . "\n";
                        echo number_format($prevTunjab) . ' (from Employee table, NIK: ' . $theEmployee->nik . ') to ' . number_format($tunjab) . " (from mst_gaji table) \n<br>";
                        $tunjab = $prevTunjab;
                    }

                    if ($maxTunjab > $tunjab) {
                        $tunjab = $maxTunjab;
                    }

                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['jumlah_newbi_band_1'] += intval($careerPath['path'][$dateFmt]['new_bi_band_1']);
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['jumlah_newbi_band_others'] += intval($careerPath['path'][$dateFmt]['new_bi_band_others']);

                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['gaji dasar'] += $gaji - $prevGajiDasar;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tbh'] += $tbh - $prevTbh;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['rekomposisi'] += $rekomposisi - $prevRekomposisi;
                    $data[$dateFmt]['SELISIH'][$careerPath['path'][$dateFmt]['caused_by']]['tunjangan jabatan'] += $tunjab - $prevTunjab;

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

                //total gaji tiap orang per bulan&tahun simulation
                $data[$dateFmt]['TOTAL GAJI INDIVIDU'] = $gaji + $tbh + $rekomposisi;

                $data[$dateFmt]['GAJI DASAR'] += $gaji;
                $data[$dateFmt]['TBH'] += $tbh;
                $data[$dateFmt]['REKOMPOSISI'] += $rekomposisi;
                $data[$dateFmt]['TUNJANGAN JABATAN'] += $tunjab;

                if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
                    $data[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($this->perc_inc_gadas) ? 0 : $data[$dateFmt]['GAJI DASAR'] * $this->perc_inc_gadas / 100);
                    $data[$dateFmt]['KENAIKAN TBH'] =
                        (empty($this->perc_inc_tbh) ? 0 : $data[$dateFmt]['TBH'] * $this->perc_inc_tbh / 100);
                    $data[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($this->perc_inc_rekomposisi) ? 0 : $data[$dateFmt]['REKOMPOSISI'] * $this->perc_inc_rekomposisi / 100);
                    $data[$dateFmt]['TOTAL KENAIKAN'] = $data[$dateFmt]['KENAIKAN GADAS'] + $data[$dateFmt]['KENAIKAN TBH'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];
                }

                $data[$dateFmt]['TOTAL GAJI DASAR'] = $data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['KENAIKAN GADAS'];
                $data[$dateFmt]['TOTAL TBH'] = $data[$dateFmt]['TBH'] + $data[$dateFmt]['KENAIKAN TBH'];
                $data[$dateFmt]['TOTAL REKOMPOSISI'] = $data[$dateFmt]['REKOMPOSISI'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];

                $data[$dateFmt]['TOTAL'] = ($data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['TBH'] + $data[$dateFmt]['REKOMPOSISI']) + (empty($data[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $data[$dateFmt]['TOTAL KENAIKAN']);


                //element bpjs ketenagakerjaan
                $totalJHT = $iuranJHT * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                //validate max upah untuk Iuran JP
                if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                    $totalJP = $iuranJP * floatval($maxJP);
                } else {
                    $totalJP = $iuranJP * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                $totalJKK = $iuranJKK * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                $totalJKM = $iuranJKM * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];


                //element bpjs kesehatan
                //validate max upah untuk Iuran JKes
                if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJkes) {
                    $totalKes = $iuranKes * floatval($maxJkes);
                } else {
                    $totalKes = $iuranKes * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                }

                $data[$dateFmt]['BPJS KETENEGAKERJAAN'] += ($totalJHT + $totalJP + $totalJKK + $totalJKM);
                $data[$dateFmt]['BPJS KESEHATAN'] += $totalKes;
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
                    } else {
                        echo "";
//                    echo "<br>\n===========\n[$currentYear] \n";
//                    echo "nik : " . $theEmployee->nik . " ngga punya PMK\n";
//                    echo "(masa kerja : " . $masaKerja . " bulan)\n\n";
                        //$data[$dateFmt]['PMK'] = 0;
                    }
                }

                if ($theEmployee->employee_category == 'PERMANENT') {
                    //CUTI BESAR => 6 TAHUN = 72 BULAN
                    if ($masaKerja == 72 || $masaKerja == 144 || $masaKerja == 216 || $masaKerja == 288 || $masaKerja == 360 || $masaKerja == 432) {
                        $data[$dateFmt]['CUTI BESAR'] = $indexCutiBesar * $data[$dateFmt]['TOTAL'];
                    } else {
                        echo "";
//                    echo "<br>\n===========\n[$currentYear] \n";
//                    echo "nik : " . $theEmployee->nik . " ngga punya CUTI BESAR\n";
//                    echo "(masa kerja : " . $masaKerja . " bulan)\n\n<br>";
                        //$data[$dateFmt]['CUTI BESAR'] = 0;
                    }
                }
            }


            $start = current(array_keys($data));
            $end = end(array_keys($data));

            $theFirstYear = intval(substr($start, 0, 4));
            $theLastYear = intval(substr($end, 0, 4));

            $theFirstMonth = 12;
            $theFirstMonths = intval(substr($start, 4, 2));
            $theMonths = substr($end, 4, 2);
            $theLastMonth = intval(substr($end, 4, 2));


            for ($i = $theFirstYear; $i <= $theLastYear; $i++) {

                if ($i < $theLastYear) {

                    //echo "pakai". $theFirstMonth."\n";
                    $element[$i . $theFirstMonth]['LAST TOTAL'] = $data[$i . $theFirstMonth]['GAJI DASAR'] + $data[$i . $theFirstMonth]['TBH'] + $data[$i . $theFirstMonth]['REKOMPOSISI'] + (empty($data[$i . $theFirstMonth]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theFirstMonth]['TOTAL KENAIKAN']);
                    $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;


                } else {

                    //echo "pakai". $theLastMonth."\n";
                    $element[$i . $theMonths]['LAST TOTAL'] = $data[$i . $theMonths]['GAJI DASAR'] + $data[$i . $theMonths]['TBH'] + $data[$i . $theMonths]['REKOMPOSISI'] + (empty($data[$i . $theMonths]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theMonths]['TOTAL KENAIKAN']);
                    $element[$i . $theMonths]['THR'] = (($indexTHR * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);

                }

            }

        }

        for ($i = $theFirstYear; $i <= $theLastYear; $i++) {
            if ($i < $theLastYear) {
                for ($y = $theFirstMonths; $y <= $theFirstMonth; $y++) {

                    if ($elementss == 'THR') {
                        echo "<br/>\n===========THR===========\n<br/>";
                        echo "Bulan :" . $y . "\n<br/>";
                        echo "Tahun :" . $theFirstYear . "\n<br/>";
                        echo "Nilai :" . $element[$i . $theFirstMonth]['THR'] . "\n<br/><br/>";
                    } else if ($elementss == 'CUTI TAHUNAN') {
                        echo "<br/>\n===========CUTI TAHUNAN===========\n<br/>";
                        echo "Bulan :" . $y . "\n<br/>";
                        echo "Tahun :" . $theFirstYear . "\n<br/>";
                        echo "Nilai :" . $element[$i . $theFirstMonth]['CUTI TAHUNAN'] . "\n<br/><br/>";
                    }
                }
            } else {
                for ($y = $theFirstMonths; $y <= $theLastMonth; $y++) {

                    if ($elementss == 'THR') {
                        echo "<br/>\n===========THR===========\n<br/>";
                        echo "Bulan :" . $y . "\n<br/>";
                        echo "Tahun :" . $theLastYear . "\n<br/>";
                        echo "Nilai :" . $element[$i . $theMonths]['THR'] . "\n<br/><br/>";
                    } else if ($elementss == 'CUTI TAHUNAN') {
                        echo "<br/>\n===========CUTI TAHUNAN===========\n<br/>";
                        echo "Bulan :" . $y . "\n<br/>";
                        echo "Tahun :" . $theLastYear . "\n<br/>";
                        echo "Nilai :" . $element[$i . $theMonths]['CUTI TAHUNAN'] . "\n<br/><br/>";
                    }
                }
            }
        }


        foreach ($data as $i => $rows) {
            $theMonth = substr($i, 4, 2);
            $theYear = substr($i, 0, 4);

            if (!empty($data[$x]['SELISIH'])) {
                foreach ($data[$x]['SELISIH'] as $row => $y) {
                    if ($row == 'EVALUASI') {

                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "JUMLAH NEW BI :" . $y['jumlah_newbi_band_others'] . "\n<br/>";
                    } else {

                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "JUMLAH NEW BI :" . $y['jumlah_newbi_band_1'] . "\n<br/>";
                    }


                    if ($elementss == 'GAJI DASAR') {
                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "Element : GAJI DASAR " . "\n<br/>";
                        echo "Nilai :" . $y['gaji dasar'] . "\n<br/>";
                    } else if ($elementss == 'TBH') {
                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "Element : TBH " . "\n<br/>";
                        echo "Nilai :" . $y['tbh'] . "\n<br/>";
                    } else if ($elementss == 'TUNJANGAN REKOMPOSISI') {
                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "Element : REKOMPOSISI " . "\n<br/>";
                        echo "Nilai :" . $y['rekomposisi'] . "\n<br/>";
                    } else if ($elementss == 'TUNJANGAN JABATAN') {
                        echo "<br/>\n===========$row===========\n<br/>";
                        echo "Bulan :" . $theMonth . "\n<br/>";
                        echo "Tahun :" . $theYear . "\n<br/>";
                        echo "Element : TUNJANGAN JABATAN " . "\n<br/>";
                        echo "Nilai :" . $y['tunjangan jabatan'] . "\n<br/>";
                    }
                }
            }

            if ($elementss == 'GAJI DASAR') {
                echo "<br/>\n===========Gaji Dasar===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['GAJI DASAR'] . "\n<br/>";

                echo "<br/>\n===========Kenaikan Gaji Dasar===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['KENAIKAN GADAS'] . "\n<br/><br/>";

                echo "<br/>\n===========Total Gaji Dasar===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['TOTAL GAJI DASAR'] . "\n<br/><br/>";
            } else if ($elementss == 'TBH') {
                echo "\n===========TBH===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['TBH'] . "\n<br/><br/>";

                echo "<br/>\n===========Kenaikan TBH===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['KENAIKAN TBH'] . "\n<br/><br/>";

                echo "<br/>\n===========Total TBH===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['TOTAL TBH'] . "\n<br/><br/>";
            } else if ($elementss == 'TUNJANGAN REKOMPOSISI') {
                echo "\n===========TUNJANGAN REKOMPOSISI===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['REKOMPOSISI'] . "\n<br/><br/>";

                echo "<br/>\n===========Kenaikan REKOMPOSISI===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['KENAIKAN REKOMPOSISI'] . "\n<br/><br/>";

                echo "<br/>\n===========Total REKOMPOSISI===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['TOTAL REKOMPOSISI'] . "\n<br/><br/>";
            } else if ($elementss == 'TUNJANGAN JABATAN') {
                echo "\n===========TUNJANGAN JABATAN===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['TUNJANGAN JABATAN'] . "\n<br/><br/>";
            } else if ($elementss == 'CUTI BESAR') {
                echo "<br/>\n===========CUTI BESAR===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['CUTI BESAR'] . "\n<br/><br/>";
            } else if ($elementss == 'PMK') {
                echo "<br/>\n===========PMK===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['PMK'] . "\n<br/><br/>";
            } else if ($elementss == 'BPJS KESEHATAN') {
                echo "<br/>\n===========BPJS KESEHATAN===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Total Gaji :" . $data[$i]['TOTAL GAJI INDIVIDU'] . "\n<br/>";
                $BPJSKES = $data[$i]['BPJS KESEHATAN'] + (empty($data[$i]['TOTAL KENAIKAN']) ? 0 : $data[$i]['TOTAL KENAIKAN']);
                echo "Nilai BPJS Kesehatan:" . $BPJSKES . "\n<br/><br/>";
            } else if ($elementss == 'BPJS KETENEGAKERJAAN') {
                echo "<br/>\n===========BPJS KETENEGAKERJAAN===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Total Gaji :" . $data[$i]['TOTAL GAJI INDIVIDU'] . "\n<br/>";
                $BPJSKET = $data[$i]['BPJS KETENEGAKERJAAN'] + (empty($data[$i]['TOTAL KENAIKAN']) ? 0 : $data[$i]['TOTAL KENAIKAN']);
                echo "Nilai BPJS Ketenagakerjaan:" . $BPJSKET . "\n<br/><br/>";
            } else if ($elementss == 'EMPLOYEE INCOME TAX') {
                echo "<br/>\n===========EMPLOYEE INCOME TAX===========\n<br/>";
                echo "Bulan :" . $theMonth . "\n<br/>";
                echo "Tahun :" . $theYear . "\n<br/>";
                echo "Nilai :" . $data[$i]['EMPLOYEE INCOME TAX'] . "\n<br/><br/>";
            }


        }


        echo "Tidak ada perhitungan element payroll $elementss<br/><br/>";


        //var_dump($data, $element);

        return $data;
    }

}
