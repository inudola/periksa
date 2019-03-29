<?php

namespace reward\models;

use DateTime;
use reward\components\Helpers;
use Yii;

/**
 * This is the model class for table "saldo_nki".
 *
 * @property int $id
 * @property string $nik
 * @property string $bi
 * @property int $smt
 * @property int $tahun
 * @property int $score
 * @property int $total
 * @property string $created_at
 * @property string $updated_at
 */
class SaldoNki extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $tbh;
    public $salary;
    public $newbi;

    public static function tableName()
    {
        return 'saldo_nki';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smt', 'tahun', 'score', 'nik', 'bi'], 'required'],
            [['smt', 'tahun'], 'integer'],
            //[['score', 'total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['nik', 'bi'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nik' => 'NIK',
            'bi' => 'Band Individu',
            'smt' => 'Semester',
            'tahun' => 'Tahun',
            'total' => 'Total',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function setTruncateData()
    {
        Yii::$app->db->createCommand()->truncateTable('saldo_nki')->execute();
    }

    public function beforeSave($insert)
    {
        $this->score = str_replace(",", ".", $this->score);

        if ($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");

        else
            $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['nik' => 'nik']);
    }

    public function getGaji()
    {
        return $this->hasOne(MstGaji::className(), ['bi' => 'bi']);
    }


    public function setBatchDetail($simId, $bulan, $tahun, $element, $description, $amount, $nik)
    {

        $batch = new BatchDetail();
        $batch->simulation_id = $simId;
        $batch->bulan = $bulan;
        $batch->tahun = $tahun;
        $batch->element = $element;
        $batch->description = $description;
        $batch->amount = $amount;
        $batch->nik = $nik;
        $batch->save();

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


    public function setGenerateSaldoNew($simId)
    {
        //$nikYangDimaksud = ['T118001', '87039', '71206', '86068', '86065', '88003', '214046', '79205'];

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


        $getSimulation = Simulation::find()->where(['id' => $simId])->one();

        Yii::$app->db->createCommand("
            DELETE FROM simulation_detail
            WHERE simulation_id = '$simId'
            ")->execute();

        Yii::$app->db->createCommand("
            DELETE FROM batch_detail
            WHERE simulation_id = '$simId'
            ")->execute();

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

        $dates = Helpers::getMonthIterator($getSimulation->start_date, $getSimulation->end_date);

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

        foreach ($niks as $theNik) {
            // get employee
            $theEmployee = Employee::find()
                ->where(['status' => Employee::ACTIVE])
                ->andWhere(['nik' => $theNik->nik])
                ->orderBy(['start_date_assignment' => SORT_DESC])
                ->one();

            $careerPath = $theEmployee->getCareerPath($theEmployee->start_date_assignment, $getSimulation->end_date);

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
                $currentSemester = ceil(intval($date->format('m')) / 6);  // bulan ini semester berapa?
                $currentYear = intval($date->format('Y'));

                $theInsentif = Insentif::find()->where([
                    'nik'   =>  $theNik->nik,
                    'tahun' => $date->format('Y'),
                ])
                    ->andWhere(['smt' => $currentSemester])
                    ->andWhere(['tahun' => $currentYear])
                    ->orderBy(['smt' => SORT_DESC])
                    ->one();

                $dateFmt = $date->format('Ym');

                $prevDate = clone $date;
                $prevDate = $prevDate->modify('-1 month');
                $prevDateFmt = $prevDate->format('Ym');


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
                        echo "\n===========\nnew Rekomposisi is insane!\n";
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


                if ($getSimulation->perc_inc_gadas > 0 || $getSimulation->perc_inc_tbh > 0 || $getSimulation->perc_inc_rekomposisi > 0) {
                    $data[$dateFmt]['KENAIKAN GADAS'] =
                        (empty($getSimulation->perc_inc_gadas) ? 0 : $data[$dateFmt]['GAJI DASAR'] * $getSimulation->perc_inc_gadas / 100);
                    $data[$dateFmt]['KENAIKAN TBH'] =
                        (empty($getSimulation->perc_inc_tbh) ? 0 : $data[$dateFmt]['TBH'] * $getSimulation->perc_inc_tbh / 100);
                    $data[$dateFmt]['KENAIKAN REKOMPOSISI'] =
                        (empty($getSimulation->perc_inc_rekomposisi) ? 0 : $data[$dateFmt]['REKOMPOSISI'] * $getSimulation->perc_inc_rekomposisi / 100);
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
                $data[$dateFmt]['EMPLOYEE INCOME TAX'] = (($data[$dateFmt]['TOTAL'] + $data[$dateFmt]['TUNJANGAN JABATAN'])/(1-$tax)) * $tax ;


                //Insentif Semesteran
                $band = intval($theEmployee->band);
//                if ($theInsentif) {
//                    if ($theEmployee->employee_category != 'TRAINEE') {
//                        //konstanta * nkk * nku * nki * TOTAL
//                        if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
//                            if ($band == (1 || 2 || 3)) {
//                                $data[$dateFmt]['INSENTIF SEMESTERAN'] += 2.830 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
//                            } else if ($band == 4) {
//                                $data[$dateFmt]['INSENTIF SEMESTERAN'] += 3.530 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
//                            } else if ($band == 5) {
//                                $data[$dateFmt]['INSENTIF SEMESTERAN'] += 4.950 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
//                            } else if ($band == 6) {
//                                $data[$dateFmt]['INSENTIF SEMESTERAN'] += 7.800 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
//                            }
//                        } //konstanta * nki * TOTAL
//                        else if ($theEmployee->employee_category == 'CONTRACT') {
//                            $data[$dateFmt]['INSENTIF SEMESTERAN'] += 4.000 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
//                        } //konstanta(FREE INPUT) * TOTAL
//                        else if ($theEmployee->employee_category == 'CONTRACT PROF' && $theEmployee->employee_category == 'EXPATRIATE') {
//                            $data[$dateFmt]['INSENTIF SEMESTERAN'] += $indexISContractProf * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
//                        } //konstanta(FREE INPUT) * TOTAL
//                        else if ($theEmployee->employee_category == 'TELKOM') {
//                            $data[$dateFmt]['INSENTIF SEMESTERAN'] += $indexISTelkom * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
//                        }
//                    }
//                }

                //Insentif Ekstra
                if ($theEmployee->employee_category != 'TRAINEE' || $theEmployee->employee_category != 'CONTRACT PROF' || $theEmployee->employee_category != 'EXPATRIATE' ) {
                    //konstanta * TOTAL
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'PROBATION') {
                        if ($band == 1) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIE1 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                        } else if ($band == 2) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIE2 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                        } else if ($band == 3) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIE3 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                        } else if ($band == 4) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIE4 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                        } else if ($band == 5) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIE5 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                        } else if ($band == 6) {
                            $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIE6 * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                        }
                    } //konstanta * TOTAL
                    else if ($theEmployee->employee_category == 'CONTRACT') {
                        $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIEContract * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                    //konstanta(FREE INPUT) * TOTAL
                    else if ($theEmployee->employee_category == 'TELKOM') {
                        $data[$dateFmt]['INSENTIF EKSTRA'] += $indexIETelkom * $data[$dateFmt]['TOTAL GAJI INDIVIDU'];
                    }
                }


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

                //CUTI BESAR => 6 TAHUN = 72 BULAN
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
            $theFirstMonths = intval(substr($start, 4, 2));
            $theMonths = substr($end, 4, 2);
            $theLastMonth = intval(substr($end, 4, 2));


            for ($i = $theFirstYear; $i <= $theLastYear; $i++) {

                if ($i < $theLastYear) {

                    //echo "pakai". $theFirstMonth."\n";
                    $element[$i . $theFirstMonth]['LAST TOTAL'] = $data[$i . $theFirstMonth]['GAJI DASAR'] + $data[$i . $theFirstMonth]['TBH'] + $data[$i . $theFirstMonth]['REKOMPOSISI'] + (empty($data[$i . $theFirstMonth]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theFirstMonth]['TOTAL KENAIKAN']);

                    //THR
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR2 * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    }
                    else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * ($element[$i . $theFirstMonth]['LAST TOTAL'] - $data[$i . $theFirstMonth]['REKOMPOSISI'])) / $theFirstMonth;
                    }
                    else if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $data[$i . $theFirstMonth]['GAJI DASAR']) / $theFirstMonth;
                    }
                    else {
                        $element[$i . $theFirstMonth]['THR'] = ($indexTHR * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    }

                    //CUTI TAHUNAN
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theFirstMonth]['CUTI TAHUNAN'] = ($indexTunjCuti * $element[$i . $theFirstMonth]['LAST TOTAL']) / $theFirstMonth;
                    }
                    else if ($theEmployee->employee_category == 'CONTRACT') {
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
                    $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX'] = (($element[$i . $theFirstMonth]['THR'] + $element[$i . $theFirstMonth]['CUTI TAHUNAN'])/(1-$tax)) * $tax ;

                }
                else {

                    //echo "pakai". $theLastMonth."\n";

                    $element[$i . $theMonths]['LAST TOTAL'] = $data[$i . $theMonths]['GAJI DASAR'] + $data[$i . $theMonths]['TBH'] + $data[$i . $theMonths]['REKOMPOSISI'] + (empty($data[$i . $theMonths]['TOTAL KENAIKAN']) ? 0 : $data[$i . $theMonths]['TOTAL KENAIKAN']);


                    //THR
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR2 * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    }
                    else if ($theEmployee->employee_category == 'CONTRACT') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * ($element[$i . $theMonths]['LAST TOTAL'] - $data[$i . $theMonths]['REKOMPOSISI'])) / $theLastMonth) / (12 / $theLastMonth);
                    }
                    else if ($theEmployee->employee_category == 'TRAINEE') {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * $data[$i . $theMonths]['GAJI DASAR']) / $theLastMonth) / (12 / $theLastMonth);
                    }
                    else {
                        $element[$i . $theMonths]['THR'] = (($indexTHR * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    }

                    //CUTI TAHUNAN
                    if ($theEmployee->employee_category == 'PERMANENT' || $theEmployee->employee_category == 'TELKOM') {
                        $element[$i . $theMonths]['CUTI TAHUNAN'] = (($indexTunjCuti * $element[$i . $theMonths]['LAST TOTAL']) / $theLastMonth) / (12 / $theLastMonth);
                    }
                    else if ($theEmployee->employee_category == 'CONTRACT') {
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
                    $element[$i . $theMonths]['EMPLOYEE INCOME TAX'] = (($element[$i . $theMonths]['THR'] + $element[$i . $theMonths]['CUTI TAHUNAN'])/(1-$tax)) * $tax ;

                }

            }

        }

        for ($i = $theFirstYear; $i <= $theLastYear; $i++) {
            if ($i < $theLastYear) {
                for ($y = $theFirstMonths; $y <= $theFirstMonth; $y++) {
                    $this->setElement($simId, $y, $theFirstYear, 'EMPLOYEES INCOME TAX', $element[$i . $theFirstMonth]['EMPLOYEE INCOME TAX']);
                    $this->setElement($simId, $y, $theFirstYear, 'OTHER ALLOWANCE', $element[$i . $theFirstMonth]['THR'] + $element[$i . $theFirstMonth]['CUTI TAHUNAN']);

                    $this->setElement($simId, $y, $theFirstYear, 'TUNJANGAN AKHIR TAHUN', $element[$i . $theFirstMonth]['TUNJANGAN AKHIR TAHUN']);
                    $this->setElement($simId, $y, $theFirstYear, 'UANG SAKU AKHIR PROGRAM', $element[$i . $theFirstMonth]['UANG SAKU AKHIR PROGRAM']);

                    //$this->setElement($simId, $y, $theFirstYear, 'THR', $element[$i . $theFirstMonth]['THR']);
                    //$this->setElement($simId, $y, $theFirstYear, 'CUTI TAHUNAN', $element[$i . $theFirstMonth]['CUTI TAHUNAN']);

                }
            } else {
                for ($y = $theFirstMonths; $y <= $theLastMonth; $y++) {
                    $this->setElement($simId, $y, $theLastYear, 'EMPLOYEES INCOME TAX', $element[$i . $theMonths]['EMPLOYEE INCOME TAX']);
                    $this->setElement($simId, $y, $theLastYear, 'OTHER ALLOWANCE', $element[$i . $theMonths]['THR'] + $element[$i . $theMonths]['CUTI TAHUNAN']);

                    $this->setElement($simId, $y, $theLastYear, 'TUNJANGAN AKHIR TAHUN', $element[$i . $theMonths]['TUNJANGAN AKHIR TAHUN']);
                    $this->setElement($simId, $y, $theLastYear, 'UANG SAKU AKHIR PROGRAM', $element[$i . $theMonths]['UANG SAKU AKHIR PROGRAM']);

                    //$this->setElement($simId, $y, $theLastYear, 'THR', $element[$i . $theMonths]['THR']);
                    //$this->setElement($simId, $y, $theLastYear, 'CUTI TAHUNAN', $element[$i . $theMonths]['CUTI TAHUNAN']);

                }
            }
        }


        foreach ($data as $x => $rows) {

            $theYear = substr($x, 0, 4);
            $theMonth = substr($x, 4, 2);

            if (!empty($data[$x]['SELISIH'])) {
                foreach ($data[$x]['SELISIH'] as $row => $y) {

                    $array = $y['nik'];

                    //$values = array_map('', $array);
                    $imploded = implode(',', $array);


                    if ($row == 'EVALUASI') {
                        $this->setBatchDetail($simId, $theMonth, $theYear,'JUMLAH NEW BI',  $row, $y['jumlah_newbi_band_others'], $imploded);
                    }else {
                        $this->setBatchDetail($simId, $theMonth, $theYear,'JUMLAH NEW BI',  $row, $y['jumlah_newbi_band_1'], $imploded);
                    }

                    $this->setBatchDetail($simId, $theMonth, $theYear,'BASE SALARIES',  $row, $y['gaji dasar'], '');
                    $this->setBatchDetail($simId, $theMonth, $theYear,'FUNCTIONAL ALLOWANCES', $row, $y['tunjangan jabatan'], '');
                    $this->setBatchDetail($simId, $theMonth, $theYear,'LIVING COST ALLOWANCES', $row, $y['tbh'], '');
                    $this->setBatchDetail($simId, $theMonth, $theYear,'TUNJANGAN REKOMPOSISI', $row, $y['rekomposisi'], '');
                }
            }

            $this->setElement($simId, $theMonth, $theYear, 'BASE SALARIES', $data[$x]['TOTAL GAJI DASAR']);
            $this->setElement($simId, $theMonth, $theYear, 'FUNCTIONAL ALLOWANCES', $data[$x]['TUNJANGAN JABATAN']);
            $this->setElement($simId, $theMonth, $theYear, 'LIVING COST ALLOWANCES', $data[$x]['TOTAL TBH']);
            $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES INCOME TAX', $data[$x]['EMPLOYEE INCOME TAX']);
            $this->setElement($simId, $theMonth, $theYear, 'EMPLOYEES BPJS', $data[$x]['BPJS KESEHATAN'] + $data[$x]['BPJS KETENEGAKERJAAN'] + (empty($data[$x]['TOTAL KENAIKAN']) ? 0 : $data[$x]['TOTAL KENAIKAN']));

            $this->setElement($simId, $theMonth, $theYear, 'TUNJANGAN REKOMPOSISI', $data[$x]['TOTAL REKOMPOSISI']);
            //$this->setElement($simId, $theMonth, $theYear, 'BPJS KESEHATAN', $data[$x]['BPJS KESEHATAN'] + (empty($data[$x]['TOTAL KENAIKAN']) ? 0 : $data[$x]['TOTAL KENAIKAN']));
            //$this->setElement($simId, $theMonth, $theYear, 'BPJS KETENEGAKERJAAN', $data[$x]['BPJS KETENEGAKERJAAN'] + (empty($data[$x]['TOTAL KENAIKAN']) ? 0 : $data[$x]['TOTAL KENAIKAN']));
            $this->setElement($simId, $theMonth, $theYear, 'PENGHARGAAN MASA KERJA', $data[$x]['PMK']);
            $this->setElement($simId, $theMonth, $theYear, 'CUTI BESAR', $data[$x]['CUTI BESAR']);

            $this->setElement($simId, $theMonth, $theYear, 'INSENTIF EKSTRAR', $data[$i]['INSENTIF EKSTRA']);
            $this->setElement($simId, $theMonth, $theYear, 'INSENTIF EKSTRAR', $data[$i]['INSENTIF EKSTRA']);

        }


        var_dump($data);

        return $data;
    }


}

