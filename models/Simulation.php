<?php

namespace reward\models;

use DateTime;
use reward\components\Helpers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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


    public function setElement($bulan, $tahun, $element, $amount, $group)
    {

        $simulation = new SimulationDetail();
        $simulation->simulation_id = $this->id;
        $simulation->bulan = $bulan;
        $simulation->tahun = $tahun;
        $simulation->element = $element;
        $simulation->amount = $amount;
        $simulation->n_group = $group;
        $simulation->save();
    }


    public function setBasicElementsNew()
    {
        //get value from setting model
        $indexTHR = Setting::getBaseSetting(Setting::INDEX_THR_1);
        $indexTHR2 = Setting::getBaseSetting(Setting::INDEX_THR_2);
        $indexTA = Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_AKHIR_TAHUN);
        $indexUangSakuAP = Setting::getBaseSetting(Setting::INDEX_UANG_SAKU_AKHIR_PROGRAM);

        $indexTunjCuti = Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_CUTI);
        $tax = Setting::getBaseSetting(Setting::INDEX_TAX);
        $indexCutiBesar = Setting::getBaseSetting(Setting::INDEX_CUTI_BESAR);

        //BPJS
        $maxJP = Setting::getBaseSetting(Setting::INDEX_JP_MAX);
        $maxJkes = Setting::getBaseSetting(Setting::INDEX_JKES_MAX);
        $iuranKes = Setting::getBaseSetting(Setting::IURAN_KES);
        $iuranJHT = Setting::getBaseSetting(Setting::IURAN_JHT);
        $iuranJP = Setting::getBaseSetting(Setting::IURAN_JP);
        $iuranJKK = Setting::getBaseSetting(Setting::IURAN_JKK);
        $iuranJKM = Setting::getBaseSetting(Setting::IURAN_JKM);

        //value PMK Structure
        $indexPMK5 = Setting::getBaseSetting(Setting::INDEX_PMK_5);
        $indexPMK10 = Setting::getBaseSetting(Setting::INDEX_PMK_10);
        $indexPMK15 = Setting::getBaseSetting(Setting::INDEX_PMK_15);
        $indexPMK20 = Setting::getBaseSetting(Setting::INDEX_PMK_20);
        $indexPMK25 = Setting::getBaseSetting(Setting::INDEX_PMK_25);
        $indexPMK30 = Setting::getBaseSetting(Setting::INDEX_PMK_30);

        //IE
        $indexIE1 = Setting::getBaseSetting(Setting::INDEX_IE_1);
        $indexIE2 = Setting::getBaseSetting(Setting::INDEX_IE_2);
        $indexIE3 = Setting::getBaseSetting(Setting::INDEX_IE_3);
        $indexIE4 = Setting::getBaseSetting(Setting::INDEX_IE_4);
        $indexIE5 = Setting::getBaseSetting(Setting::INDEX_IE_5);
        $indexIE6 = Setting::getBaseSetting(Setting::INDEX_IE_6);
        $indexIEContract = Setting::getBaseSetting(Setting::INDEX_IE_CONTRACT);
        $indexIETelkom = Setting::getBaseSetting(Setting::INDEX_IE_TELKOM);

        //INSENTIF SEMESTERAN
        $indexISTelkom = Setting::getBaseSetting(Setting::INDEX_IS_TELKOM);
        $indexISContractProf = Setting::getBaseSetting(Setting::INDEX_IS_CONTRACT_PROF);
        $indexNkk = Setting::getBaseSetting(Setting::INDEX_NKK);
        $indexNki = Setting::getBaseSetting(Setting::INDEX_NKI);
        $indexNku = Setting::getBaseSetting(Setting::INDEX_NKU);


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
                    $data[$dateFmt]['TOTAL GAJI INDIVIDU'] = $gaji + $tbh + $rekomposisi + (empty($tunjab) ? 0 : $tunjab);
                }
                $data[$dateFmt]['GAJI DASAR'] += $gaji;
                $data[$dateFmt]['TBH'] += $tbh;
                $data[$dateFmt]['REKOMPOSISI'] += $rekomposisi;

                if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
                    $data[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($this->perc_inc_gadas) ? 0 : $data[$dateFmt]['GAJI DASAR'] * $this->perc_inc_gadas / 100);
                    $data[$dateFmt]['KENAIKAN TBH'] =
                        (empty($this->perc_inc_tbh) ? 0 : $data[$dateFmt]['TBH'] * $this->perc_inc_tbh / 100);
                    $data[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($this->perc_inc_rekomposisi) ? 0 : $data[$dateFmt]['REKOMPOSISI'] * $this->perc_inc_rekomposisi / 100);
                    $data[$dateFmt]['TOTAL KENAIKAN'] = $data[$dateFmt]['KENAIKAN GADAS'] + $data[$dateFmt]['KENAIKAN TBH'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];
                }


                //=========================================1. BASE SALARIES==========================================
                $data[$dateFmt]['TOTAL GAJI DASAR'] = $data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['KENAIKAN GADAS'];


                //=========================================2. PERFORMANCE INCENTIVES=================================
                //a. Insentif Semesteran
                $band = intval(substr($careerPath['path'][$dateFmt]['bi'], 0, 1));


                $nkk = Setting::getConvertionNkk($indexNkk);
                $nku = Setting::getConvertionNku($indexNku);
                $nki = Setting::getConvertionNki($indexNki);

                if ($band > 0) {

                    //konstanta * nkk * nku * nki * TOTAL
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION' || $careerPath['path'][$dateFmt]['emp_category'] == 'PERMANENT') {
                        if ($band == 1 || $band == 2 || $band == 3) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 2.830 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 4) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 3.530 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 5) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 4.950 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 6) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 7.800 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        }
                    } //konstanta(FREE INPUT) * TOTAL
                    else if ($theEmployee->employee_category == 'TELKOM') {
                        $data[$dateFmt]['INSENTIF SEMESTERAN'] = $indexISTelkom * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                } else {
                    //konstanta * nki * TOTAL
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $data[$dateFmt]['INSENTIF SEMESTERAN'] = 4.000 * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                    //konstanta(FREE INPUT) * TOTAL
                    else if ($theEmployee->employee_category == 'CONTRACT PROF' && $theEmployee->employee_category == 'EXPATRIATE') {
                        $data[$dateFmt]['INSENTIF SEMESTERAN'] = $indexISContractProf * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                }


                //b. Insentif Ekstra
                if ($band > 0) {
                    //konstanta * TOTAL
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION' || $careerPath['path'][$dateFmt]['emp_category'] == 'PERMANENT') {
                        if ($band == 1) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE1 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 2) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE2 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 3) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE3 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 4) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE4 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 5) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE5 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 6) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE6 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        }
                    } //konstanta(FREE INPUT) * TOTAL
                    else if ($theEmployee->employee_category == 'TELKOM') {
                        $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIETelkom * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                }
                else {
                    //konstanta * TOTAL
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIEContract * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;

                    }
                }


                //=========================================4. FUNCTIONAL ALLOWANCES==================================
                $data[$dateFmt]['TUNJANGAN JABATAN'] += $tunjab;


                //=========================================5. LIVING COST ALLOWANCES=================================
                $data[$dateFmt]['TOTAL TBH'] = $data[$dateFmt]['TBH'] + $data[$dateFmt]['KENAIKAN TBH'];


                //=========================================8. OTHER ALLOWANCE========================================
                $data[$dateFmt]['TOTAL'] = ($data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['TBH'] + $data[$dateFmt]['REKOMPOSISI']) + (empty($data[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $data[$dateFmt]['TOTAL KENAIKAN']);

                //THR
                if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM' || $band >= 1) {
                    $data[$dateFmt]['THR'] = $indexTHR2 * $data[$dateFmt]['TOTAL'] / 12;
                } else if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['THR'] = $indexTHR * $data[$dateFmt]['TOTAL'] / 12;
                } else if ($careerPath['path'][$dateFmt]['emp_category'] == 'TRAINEE') {
                    $data[$dateFmt]['THR'] = $indexTHR * $data[$dateFmt]['TOTAL'] / 6;
                } else {
                    $data[$dateFmt]['THR'] = $indexTHR * $data[$dateFmt]['TOTAL'] / 12;
                }
                //CUTI TAHUNAN
                if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM' || $band >= 1) {
                    $data[$dateFmt]['CUTI TAHUNAN'] = $indexTunjCuti * $data[$dateFmt]['TOTAL'] / 12;
                } else if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['CUTI TAHUNAN'] = $indexTunjCuti * $data[$dateFmt]['TOTAL'] / 12;
                }
                //TUNJANGAN AKHIR TAHUN
                if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['TUNJANGAN AKHIR TAHUN'] = $indexTA * $data[$dateFmt]['TOTAL'] / 12;
                }
                //UANG SAKU AKHIR PROGRAM
                if ($band < 1) {
                    if ($theEmployee->employee_category == 'TRAINEE') {
                        $data[$dateFmt]['UANG SAKU AKHIR PROGRAM'] = $indexUangSakuAP * $data[$dateFmt]['TOTAL'] / 6;
                    }
                }


                $data[$dateFmt]['OTHER ALLOWANCES'] = $data[$dateFmt]['THR'] + $data[$dateFmt]['CUTI TAHUNAN'] + $data[$dateFmt]['TUNJANGAN AKHIR TAHUN'] + $data[$dateFmt]['UANG SAKU AKHIR PROGRAM'];


                //=========================================9. TUNJANGAN REKOMPOSISI=================================
                $data[$dateFmt]['TOTAL REKOMPOSISI'] = $data[$dateFmt]['REKOMPOSISI'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];


                //=========================================6. EMPLOYEES INCOME TAX===================================

                $data[$dateFmt]['EMPLOYEES INCOME TAX'] = ($data[$dateFmt]['TOTAL GAJI DASAR'] + //base salaries
                        ($data[$dateFmt]['INSENTIF SEMESTERAN'] + $data[$dateFmt]['INSENTIF EKSTRA']) + //performance incentives
                        $data[$dateFmt]['TUNJANGAN JABATAN'] + //functional allowances
                        $data[$dateFmt]['TOTAL TBH'] + //living cost allowances
                        $data[$dateFmt]['OTHER ALLOWANCES'] +
                        $data[$dateFmt]['TOTAL REKOMPOSISI']) / (1 - $tax) * $tax;


                //=========================================7. EMPLOYEES BPJS=========================================
                //element bpjs ketenagakerjaan
                if ($theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF') {
                    $konstantaTotal = $iuranJHT + $iuranJKM + $iuranJKK;
                }
                else if ($careerPath['path'][$dateFmt]['emp_category'] == 'TRAINEE') {
                    $konstantaTotal = $iuranJKM + $iuranJKK;

                } else {
                    $konstantaTotal = $iuranJHT + $iuranJKM + $iuranJKK;

                }

                if ($theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF') {
                    $bpjsKetenagakerjaan = $konstantaTotal * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));;
                }
                else if ($careerPath['path'][$dateFmt]['emp_category'] == 'TRAINEE') {
                    $bpjsKetenagakerjaan = $konstantaTotal * $data[$dateFmt]['TOTAL GAJI DASAR'];
                } else {
                    $bpjsKetenagakerjaan = $konstantaTotal * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));;
                }

                //bpjs jaminan pensiun
                if ($band > 0 ) {
                    //validate max upah untuk Iuran JP
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                        $totalJP = $iuranJP * floatval($maxJP);
                    } else {
                        $totalJP = $iuranJP * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));
                    }
                }
                else {
                    if ($theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF' || $theEmployee->employee_category == 'EXPATRIATE') {
                        if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                            $totalJP = $iuranJP * floatval($maxJP);
                        } else {
                            $totalJP = $iuranJP * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));
                        }
                    }
                }

                //element bpjs kesehatan
                if ($band > 0 || $theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF' || $theEmployee->employee_category == 'EXPATRIATE') {
                    //validate max upah untuk Iuran JKes
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJkes) {
                        $totalKes = $iuranKes * floatval($maxJkes);
                    } else {
                        $totalKes = $iuranKes * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                }


                $data[$dateFmt]['BPJS KETENAGAKERJAAN'] = $bpjsKetenagakerjaan;
                $data[$dateFmt]['BPJS KESEHATAN'] = $totalKes;
                $data[$dateFmt]['BPJS PENSIUN'] = $totalJP;


                $data[$dateFmt]['TOTAL'] = ($data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['TBH'] + $data[$dateFmt]['REKOMPOSISI']) + (empty($data[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $data[$dateFmt]['TOTAL KENAIKAN']);

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

            $this->setElement($theMonth, $theYear, 'BASE SALARIES', $data[$i]['TOTAL GAJI DASAR'], 1);

            $this->setElement($theMonth, $theYear, 'INSENTIF SEMESTERAN', $data[$i]['INSENTIF SEMESTERAN'], 3);
            $this->setElement($theMonth, $theYear, 'INSENTIF EKSTRA', $data[$i]['INSENTIF EKSTRA'], 3);

            $this->setElement($theMonth, $theYear, 'FUNCTIONAL ALLOWANCES', $data[$i]['TUNJANGAN JABATAN'], 4);
            $this->setElement($theMonth, $theYear, 'LIVING COST ALLOWANCES', $data[$i]['TOTAL TBH'], 5);
            $this->setElement($theMonth, $theYear, 'EMPLOYEES INCOME TAX', $data[$i]['EMPLOYEES INCOME TAX'], 6);

            $this->setElement($theMonth, $theYear, 'BPJS KETENAGAKERJAAN', $data[$i]['BPJS KETENAGAKERJAAN'], 7);
            $this->setElement($theMonth, $theYear, 'BPJS KESEHATAN', $data[$i]['BPJS KESEHATAN'], 7);
            $this->setElement($theMonth, $theYear, 'BPJS PENSIUN', $data[$i]['BPJS PENSIUN'], 7);

            $this->setElement($theMonth, $theYear, 'THR', $data[$i]['THR'], 15);
            $this->setElement($theMonth, $theYear, 'CUTI TAHUNAN', $data[$i]['CUTI TAHUNAN'], 15);
            $this->setElement($theMonth, $theYear, 'TUNJANGAN AKHIR TAHUN', $data[$i]['TUNJANGAN AKHIR TAHUN'], 15);
            $this->setElement($theMonth, $theYear, 'UANG SAKU AKHIR PROGRAM', $data[$i]['UANG SAKU AKHIR PROGRAM'], 15);

            $this->setElement($theMonth, $theYear, 'TUNJANGAN REKOMPOSISI', $data[$i]['TOTAL REKOMPOSISI'], 16);

            $this->setElement($theMonth, $theYear, 'PENGHARGAAN MASA KERJA', $data[$i]['PMK'], null);
            $this->setElement($theMonth, $theYear, 'CUTI BESAR', $data[$i]['CUTI BESAR'], null);

        }


        var_dump($data, $element);

        return $data;
    }


    public function setSimulationTest()
    {

        //get value from setting model
        $indexTHR = Setting::getBaseSetting(Setting::INDEX_THR_1);
        $indexTHR2 = Setting::getBaseSetting(Setting::INDEX_THR_2);
        $indexTA = Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_AKHIR_TAHUN);
        $indexUangSakuAP = Setting::getBaseSetting(Setting::INDEX_UANG_SAKU_AKHIR_PROGRAM);

        $indexTunjCuti = Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_CUTI);
        $tax = Setting::getBaseSetting(Setting::INDEX_TAX);
        $indexCutiBesar = Setting::getBaseSetting(Setting::INDEX_CUTI_BESAR);

        //BPJS
        $maxJP = Setting::getBaseSetting(Setting::INDEX_JP_MAX);
        $maxJkes = Setting::getBaseSetting(Setting::INDEX_JKES_MAX);
        $iuranKes = Setting::getBaseSetting(Setting::IURAN_KES);
        $iuranJHT = Setting::getBaseSetting(Setting::IURAN_JHT);
        $iuranJP = Setting::getBaseSetting(Setting::IURAN_JP);
        $iuranJKK = Setting::getBaseSetting(Setting::IURAN_JKK);
        $iuranJKM = Setting::getBaseSetting(Setting::IURAN_JKM);

        //value PMK Structure
        $indexPMK5 = Setting::getBaseSetting(Setting::INDEX_PMK_5);
        $indexPMK10 = Setting::getBaseSetting(Setting::INDEX_PMK_10);
        $indexPMK15 = Setting::getBaseSetting(Setting::INDEX_PMK_15);
        $indexPMK20 = Setting::getBaseSetting(Setting::INDEX_PMK_20);
        $indexPMK25 = Setting::getBaseSetting(Setting::INDEX_PMK_25);
        $indexPMK30 = Setting::getBaseSetting(Setting::INDEX_PMK_30);

        //IE
        $indexIE1 = Setting::getBaseSetting(Setting::INDEX_IE_1);
        $indexIE2 = Setting::getBaseSetting(Setting::INDEX_IE_2);
        $indexIE3 = Setting::getBaseSetting(Setting::INDEX_IE_3);
        $indexIE4 = Setting::getBaseSetting(Setting::INDEX_IE_4);
        $indexIE5 = Setting::getBaseSetting(Setting::INDEX_IE_5);
        $indexIE6 = Setting::getBaseSetting(Setting::INDEX_IE_6);
        $indexIEContract = Setting::getBaseSetting(Setting::INDEX_IE_CONTRACT);
        $indexIETelkom = Setting::getBaseSetting(Setting::INDEX_IE_TELKOM);

        //INSENTIF SEMESTERAN
        $indexISTelkom = Setting::getBaseSetting(Setting::INDEX_IS_TELKOM);
        $indexISContractProf = Setting::getBaseSetting(Setting::INDEX_IS_CONTRACT_PROF);
        $indexNkk = Setting::getBaseSetting(Setting::INDEX_NKK);
        $indexNki = Setting::getBaseSetting(Setting::INDEX_NKI);
        $indexNku = Setting::getBaseSetting(Setting::INDEX_NKU);


        //$nikYangDimaksud = ['T118001', '87039', '71206', '86068', '86065', '88003', '214046', '79205'];

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
                    $data[$dateFmt]['TOTAL GAJI INDIVIDU'] = $gaji + $tbh + $rekomposisi + (empty($tunjab) ? 0 : $tunjab);
                }
                $data[$dateFmt]['GAJI DASAR'] += $gaji;
                $data[$dateFmt]['TBH'] += $tbh;
                $data[$dateFmt]['REKOMPOSISI'] += $rekomposisi;

                if ($this->perc_inc_gadas > 0 || $this->perc_inc_tbh > 0 || $this->perc_inc_rekomposisi > 0) {
                    $data[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($this->perc_inc_gadas) ? 0 : $data[$dateFmt]['GAJI DASAR'] * $this->perc_inc_gadas / 100);
                    $data[$dateFmt]['KENAIKAN TBH'] =
                        (empty($this->perc_inc_tbh) ? 0 : $data[$dateFmt]['TBH'] * $this->perc_inc_tbh / 100);
                    $data[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($this->perc_inc_rekomposisi) ? 0 : $data[$dateFmt]['REKOMPOSISI'] * $this->perc_inc_rekomposisi / 100);
                    $data[$dateFmt]['TOTAL KENAIKAN'] = $data[$dateFmt]['KENAIKAN GADAS'] + $data[$dateFmt]['KENAIKAN TBH'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];
                }


                //=========================================1. BASE SALARIES==========================================
                $data[$dateFmt]['TOTAL GAJI DASAR'] = $data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['KENAIKAN GADAS'];


                //=========================================2. PERFORMANCE INCENTIVES=================================
                //a. Insentif Semesteran
                $band = intval(substr($careerPath['path'][$dateFmt]['bi'], 0, 1));


                $nkk = Setting::getConvertionNkk($indexNkk);
                $nku = Setting::getConvertionNku($indexNku);
                $nki = Setting::getConvertionNki($indexNki);

                if ($band > 0) {

                    //konstanta * nkk * nku * nki * TOTAL
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION' || $careerPath['path'][$dateFmt]['emp_category'] = 'PERMANENT') {
                        if ($band == 1 || $band == 2 || $band == 3) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 2.830 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 4) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 3.530 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 5) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 4.950 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 6) {
                            $data[$dateFmt]['INSENTIF SEMESTERAN'] = 7.800 * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        }
                    } //konstanta(FREE INPUT) * TOTAL
                    else if ($theEmployee->employee_category == 'TELKOM') {
                        $data[$dateFmt]['INSENTIF SEMESTERAN'] = $indexISTelkom * $nkk * $nku * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                } else {
                    //konstanta * nki * TOTAL
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $data[$dateFmt]['INSENTIF SEMESTERAN'] = 4.000 * $nki * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                    //konstanta(FREE INPUT) * TOTAL
                    else if ($theEmployee->employee_category == 'CONTRACT PROF' && $theEmployee->employee_category == 'EXPATRIATE') {
                        $data[$dateFmt]['INSENTIF SEMESTERAN'] = $indexISContractProf * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                }


                //b. Insentif Ekstra
                if ($band > 0) {
                    //konstanta * TOTAL
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION' || $careerPath['path'][$dateFmt]['emp_category'] = 'PERMANENT') {
                        if ($band == 1) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE1 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 2) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE2 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 3) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE3 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 4) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE4 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 5) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE5 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        } else if ($band == 6) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIE6 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                        }
                    } //konstanta(FREE INPUT) * TOTAL
                    else if ($theEmployee->employee_category == 'TELKOM') {
                        $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIETelkom * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;
                    }
                }
                else {
                    //konstanta * TOTAL
                    if ($theEmployee->employee_category == 'CONTRACT') {
                        $data[$dateFmt]['INSENTIF EKSTRA'] = $indexIEContract * $data[$dateFmt]['TOTAL GAJI INDIVIDU'] / 12;

                    }
                }


                //=========================================4. FUNCTIONAL ALLOWANCES==================================
                $data[$dateFmt]['TUNJANGAN JABATAN'] += $tunjab;


                //=========================================5. LIVING COST ALLOWANCES=================================
                $data[$dateFmt]['TOTAL TBH'] = $data[$dateFmt]['TBH'] + $data[$dateFmt]['KENAIKAN TBH'];


                //=========================================8. OTHER ALLOWANCE========================================
                $data[$dateFmt]['TOTAL'] = ($data[$dateFmt]['GAJI DASAR'] + $data[$dateFmt]['TBH'] + $data[$dateFmt]['REKOMPOSISI']) + (empty($data[$dateFmt]['TOTAL KENAIKAN']) ? 0 : $data[$dateFmt]['TOTAL KENAIKAN']);

                //THR
                if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM' || $band >= 1) {
                    $data[$dateFmt]['THR'] = $indexTHR2 * $data[$dateFmt]['TOTAL'] / 12;
                } else if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['THR'] = $indexTHR * $data[$dateFmt]['TOTAL'] / 12;
                } else if ($careerPath['path'][$dateFmt]['emp_category'] == 'TRAINEE') {
                    $data[$dateFmt]['THR'] = $indexTHR * $data[$dateFmt]['TOTAL'] / 6;
                } else {
                    $data[$dateFmt]['THR'] = $indexTHR * $data[$dateFmt]['TOTAL'] / 12;
                }
                //CUTI TAHUNAN
                if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM' || $band >= 1) {
                    $data[$dateFmt]['CUTI TAHUNAN'] = $indexTunjCuti * $data[$dateFmt]['TOTAL'] / 12;
                } else if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['CUTI TAHUNAN'] = $indexTunjCuti * $data[$dateFmt]['TOTAL'] / 12;
                }
                //TUNJANGAN AKHIR TAHUN
                if ($theEmployee->employee_category == 'CONTRACT') {
                    $data[$dateFmt]['TUNJANGAN AKHIR TAHUN'] = $indexTA * $data[$dateFmt]['TOTAL'] / 12;
                }
                //UANG SAKU AKHIR PROGRAM
                if ($band < 1) {
                    if ($theEmployee->employee_category == 'TRAINEE')
                    $data[$dateFmt]['UANG SAKU AKHIR PROGRAM'] = $indexUangSakuAP * $data[$dateFmt]['TOTAL'] / 6;
                }


                $data[$dateFmt]['OTHER ALLOWANCES'] = $data[$dateFmt]['THR'] + $data[$dateFmt]['CUTI TAHUNAN'] + $data[$dateFmt]['TUNJANGAN AKHIR TAHUN'] + $data[$dateFmt]['UANG SAKU AKHIR PROGRAM'];


                //=========================================9. TUNJANGAN REKOMPOSISI=================================
                $data[$dateFmt]['TOTAL REKOMPOSISI'] = $data[$dateFmt]['REKOMPOSISI'] + $data[$dateFmt]['KENAIKAN REKOMPOSISI'];


                //=========================================6. EMPLOYEES INCOME TAX===================================
                $overtime = 10000000;
                $relocation = 10000000;

                $data[$dateFmt]['EMPLOYEES INCOME TAX'] = ($data[$dateFmt]['TOTAL GAJI DASAR'] + //base salaries
                        ($data[$dateFmt]['INSENTIF SEMESTERAN'] + $data[$dateFmt]['INSENTIF EKSTRA']) + //performance incentives
                        $data[$dateFmt]['TUNJANGAN JABATAN'] + //functional allowances
                        $data[$dateFmt]['TOTAL TBH'] + //living cost allowances
                        $data[$dateFmt]['OTHER ALLOWANCES'] +
                        $overtime + $relocation +
                        $data[$dateFmt]['TOTAL REKOMPOSISI']) / (1 - $tax) * $tax;


                //=========================================7. EMPLOYEES BPJS=========================================
                //element bpjs ketenagakerjaan
                if ($theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF') {
                    $konstantaTotal = $iuranJHT + $iuranJKM + $iuranJKK;
                }
                else if ($careerPath['path'][$dateFmt]['emp_category'] == 'TRAINEE') {
                    $konstantaTotal = $iuranJKM + $iuranJKK;
                    echo "masuk siniiii";
                } else {
                    $konstantaTotal = $iuranJHT + $iuranJKM + $iuranJKK;
                    echo "masuk sini";
                }

                if ($theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF') {
                    $bpjsKetenagakerjaan = $konstantaTotal * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));;
                }
                else if ($careerPath['path'][$dateFmt]['emp_category'] == 'TRAINEE') {
                    $bpjsKetenagakerjaan = $konstantaTotal * $data[$dateFmt]['TOTAL GAJI DASAR'];
                } else {
                    $bpjsKetenagakerjaan = $konstantaTotal * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));;
                }

                //bpjs jaminan pensiun
                if ($band > 0 ) {
                    //validate max upah untuk Iuran JP
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                        $totalJP = $iuranJP * floatval($maxJP);
                    } else {
                        $totalJP = $iuranJP * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));
                    }
                }
                else {
                   if ($theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF' || $theEmployee->employee_category == 'EXPATRIATE') {
                       if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJP) {
                           $totalJP = $iuranJP * floatval($maxJP);
                       } else {
                           $totalJP = $iuranJP * ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] - (empty($tunjab) ? 0 : $tunjab));
                       }
                    }
                }

                //element bpjs kesehatan
                if ($band > 0 || $theEmployee->employee_category == 'CONTRACT' || $theEmployee->employee_category == 'CONTRACT PROF' || $theEmployee->employee_category == 'EXPATRIATE') {
                    //validate max upah untuk Iuran JKes
                    if ($data[$dateFmt]['TOTAL GAJI INDIVIDU'] >= $maxJkes) {
                        $totalKes = $iuranKes * floatval($maxJkes);
                    } else {
                        $totalKes = $iuranKes * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                }


                $data[$dateFmt]['BPJS KETENAGAKERJAAN'] = $bpjsKetenagakerjaan;
                $data[$dateFmt]['BPJS KESEHATAN'] = $totalKes;
                $data[$dateFmt]['BPJS PENSIUN'] = $totalJP;


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

        }

        $formatter = \Yii::$app->formatter;
        foreach ($data as $i => $rows) {
            $theMonth = substr($i, 4, 2);
            $theYear = substr($i, 0, 4);
//            if (!empty($data[$i]['SELISIH'])) {
//                foreach ($data[$i]['SELISIH'] as $row => $y) {
//                    if ($row == 'EVALUASI') {
//
//                        echo "<br/>\n===========$row===========\n<br/>";
//                        echo "Bulan :" . $theMonth . "\n<br/>";
//                        echo "Tahun :" . $theYear . "\n<br/>";
//                        echo "JUMLAH NEW BI :" . $y['jumlah_newbi_band_others'] . "\n<br/>";
//                    } else {
//
//                        echo "<br/>\n===========$row===========\n<br/>";
//                        echo "Bulan :" . $theMonth . "\n<br/>";
//                        echo "Tahun :" . $theYear . "\n<br/>";
//                        echo "JUMLAH NEW BI :" . $y['jumlah_newbi_band_1'] . "\n<br/>";
//                    }
//
//
//                    echo "<br/>\n===========$row===========\n<br/>";
//                    echo "Bulan :" . $theMonth . "\n<br/>";
//                    echo "Tahun :" . $theYear . "\n<br/>";
//                    echo "Element : GAJI DASAR " . "\n<br/>";
//                    echo "Nilai :" . $y['gaji dasar'] . "\n<br/>";
//
//                    echo "<br/>\n===========$row===========\n<br/>";
//                    echo "Bulan :" . $theMonth . "\n<br/>";
//                    echo "Tahun :" . $theYear . "\n<br/>";
//                    echo "Element : TBH " . "\n<br/>";
//                    echo "Nilai :" . $y['tbh'] . "\n<br/>";
//
//                    echo "<br/>\n===========$row===========\n<br/>";
//                    echo "Bulan :" . $theMonth . "\n<br/>";
//                    echo "Tahun :" . $theYear . "\n<br/>";
//                    echo "Element : REKOMPOSISI " . "\n<br/>";
//                    echo "Nilai :" . $y['rekomposisi'] . "\n<br/>";
//
//                    echo "<br/>\n===========$row===========\n<br/>";
//                    echo "Bulan :" . $theMonth . "\n<br/>";
//                    echo "Tahun :" . $theYear . "\n<br/>";
//                    echo "Element : TUNJANGAN JABATAN " . "\n<br/>";
//                    echo "Nilai :" . $y['tunjangan jabatan'] . "\n<br/>";
//                }
//            }


            echo "<br/>\n===========BASE SALARIES===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TOTAL GAJI DASAR'] . "\n<br/><br/>";

            echo "\n===========PERFORMANCE INCENTIVES===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            $total = $data[$i]['INSENTIF SEMESTERAN'] + $data[$i]['INSENTIF EKSTRA'];
            echo "Nilai Insentif semesteran:" . $data[$i]['INSENTIF SEMESTERAN'] . "\n<br/>";
            echo "Nilai Insentif ekstra:" . $data[$i]['INSENTIF EKSTRA'] . "\n<br/>";
            echo "Nilai :" . $total . "\n<br/><br/>";

            echo "<br/>\n===========FUNCTIONAL ALLOWANCES===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TUNJANGAN JABATAN'] . "\n<br/><br/>";

            echo "\n===========LIVING COST ALLOWANCES===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TOTAL TBH'] . "\n<br/><br/>";

            echo "<br/>\n===========EMPLOYEES INCOME TAX===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['EMPLOYEES INCOME TAX'] . "\n<br/><br/>";

            echo "<br/>\n===========EMPLOYEES BPJS===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            $BPJS = $data[$i]['BPJS KETENAGAKERJAAN'] + $data[$i]['BPJS KESEHATAN'] + $data[$i]['BPJS PENSIUN'];
            echo "Nilai BPJS KETENAGAKERJAAN :" . $data[$i]['BPJS KETENAGAKERJAAN'] . "\n<br/>";
            echo "Nilai BPJS PENSIUN :" . $data[$i]['BPJS PENSIUN'] . "\n<br/>";
            echo "Nilai BPJS KESEHATAN :" . $data[$i]['BPJS KESEHATAN'] . "\n<br/>";
            echo "Nilai :" . $BPJS . "\n<br/><br/>";

            echo "<br/>\n===========OTHER ALLOWANCE===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            $allowances = $data[$i]['THR'] + $data[$i]['CUTI TAHUNAN'] + $data[$i]['TUNJANGAN AKHIR TAHUN'] + $data[$i]['UANG SAKU AKHIR PROGRAM'];
            echo "Nilai THR:" . $formatter->asDecimal(Html::encode($data[$i]['THR'], 2)) . "\n<br/>";
            echo "Nilai Cuti Tahunan:" . $formatter->asDecimal(Html::encode($data[$i]['CUTI TAHUNAN'], 2)) . "\n<br/>";
            echo "Nilai Tunjangan Akhir Tahun:" . $formatter->asDecimal(Html::encode($data[$i]['TUNJANGAN AKHIR TAHUN'], 2)) . "\n<br/>";
            echo "Nilai Uang Saku Akhir Program:" . $formatter->asDecimal(Html::encode($data[$i]['UANG SAKU AKHIR PROGRAM'], 2)) . "\n<br/>";
            echo "Nilai :" . $formatter->asDecimal(Html::encode($allowances, 2)) . "\n<br/><br/>";

            echo "<br/>\n===========TUNJANGAN REKOMPOSISI===========\n<br/>";
            echo "Bulan :" . $theMonth . "\n<br/>";
            echo "Tahun :" . $theYear . "\n<br/>";
            echo "Nilai :" . $data[$i]['TOTAL REKOMPOSISI'] . "\n<br/><br/>";
        }

        //var_dump($data, $element);

        return $data;
    }

}
