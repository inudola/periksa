<?php

namespace reward\models;

use reward\components\Helpers;
use Yii;

class Employee extends \common\models\Employee
{

    public $newbi;
    public $effective_date;

    private $_isAdmin = false;
    private $_isAdminProjection = false;


    public function getReward()
    {
        $employee = Yii::$app->user->identity->employee;
        $people = Employee::find()->where(['nik' => $employee->nik])->one();

        $baseReward = MstReward::findAll(['status' => MstReward::ACTIVE]);

        $allResults = [];
        // loop through all kind of reward
        foreach ($baseReward as $item) {
            $baseQuery = Reward::find()
                ->select(['mst_reward_id', 'amount'])
                ->where(['isApproved' => Reward::APPROVED])
                ->andFilterWhere(['mst_reward_id' => $item->id]);

            // get all criteria_name for this reward
            $criteriaNamesQ = RewardCriteria::find()
                ->select('criteria_name')
                ->where(['mst_reward_id' => $item->id])
                ->asArray()
                ->all();

            $criteriaNames = [];
            foreach ($criteriaNamesQ as $cn) {
                $criteriaNames[] = $cn['criteria_name'];
            }

            if (count($criteriaNames) > 0) {
                // run if there's rule in table reward_criteria
                if (in_array('band_individu', $criteriaNames)) {
                    $baseQuery->andWhere(['band_individu' => $people->bi]);
                }
                if (in_array('marital_status', $criteriaNames)) {
                    $baseQuery->andWhere(['marital_status' => $people->status_pernikahan]);
                }
                if (in_array('structural', $criteriaNames)) {
                    $baseQuery->andWhere(['structural' => $people->structural]);
                }
                if (in_array('functional', $criteriaNames)) {
                    $baseQuery->andWhere(['functional' => $people->functional]);
                }
                if (in_array('band_position', $criteriaNames)) {
                    $baseQuery->andWhere(['band_position' => $people->bp]);
                }
                if (in_array('emp_category', $criteriaNames)) {
                    $baseQuery->andWhere(['emp_category' => $people->employee_category]);
                }
                if (in_array('gender', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->gender]);
                }
                if (in_array('kota', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->kota]);
                }
            } else {
                // no rules.. default filter by band_individu
                $baseQuery->andWhere(['band_individu' => $people->bi]);
            }


            $thisResult = $baseQuery->all();


            foreach ($thisResult as $row) {

                $theRewardName = \reward\models\MstReward::find()
                    ->select(['id', 'reward_name', 'categoryId', 'icon', 'description'])
                    ->where(['id' => $row['mst_reward_id']])
                    ->all();

                foreach ($theRewardName as $rows) {

                    $rowArray = $row->toArray();

                    // only add unique value to $allResults
                    if (!in_array($rowArray, $allResults)) {
                        //$allResults[] = $rowArray;
                        $allResults[$rows['categoryId']][$rows['reward_name']][] = $rowArray;
                        //$allResults[$rows['categoryId']]['total'] += $row['amount'];

                    }
                }
            }
        }

        return $allResults;

    }

    public
    function getRewardDetail($id)
    {
        $employee = Yii::$app->user->identity->employee;
        $people = Employee::find()->where(['nik' => $employee->nik])->one(); // test Employee doang, ganti pakai yg di atas

        $baseReward = MstReward::findAll(['status' => MstReward::ACTIVE]);

        $allResults = [];
        // loop through all kind of reward
        foreach ($baseReward as $item) {
            $baseQuery = Reward::find()
                ->select(['mst_reward_id', 'amount'])
                ->where(['isApproved' => Reward::APPROVED])
                ->andFilterWhere(['mst_reward_id' => $item->id])
                ->andFilterWhere(['mst_reward_id' => $id]);

            // get all criteria_name for this reward
            $criteriaNamesQ = RewardCriteria::find()
                ->select('criteria_name')
                ->where(['mst_reward_id' => $item->id])
                ->asArray()
                ->all();

            $criteriaNames = [];
            foreach ($criteriaNamesQ as $cn) {
                $criteriaNames[] = $cn['criteria_name'];
            }

            if (count($criteriaNames) > 0) {
                // run if there's rule in table reward_criteria
                if (in_array('band_individu', $criteriaNames)) {
                    $baseQuery->andWhere(['band_individu' => $people->bi]);
                }
                if (in_array('marital_status', $criteriaNames)) {
                    $baseQuery->andWhere(['marital_status' => $people->status_pernikahan]);
                }
                if (in_array('structural', $criteriaNames)) {
                    $baseQuery->andWhere(['structural' => $people->structural]);
                }
                if (in_array('functional', $criteriaNames)) {
                    $baseQuery->andWhere(['functional' => $people->functional]);
                }
                if (in_array('band_position', $criteriaNames)) {
                    $baseQuery->andWhere(['band_position' => $people->bp]);
                }
                if (in_array('emp_category', $criteriaNames)) {
                    $baseQuery->andWhere(['emp_category' => $people->employee_category]);
                }
                if (in_array('gender', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->gender]);
                }
                if (in_array('kota', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->kota]);
                }
            } else {
                // no rules.. default filter by band_individu
                $baseQuery->andWhere(['band_individu' => $people->bi]);
            }


            $thisResult = $baseQuery->all();

            foreach ($thisResult as $row) {
                $rowArray = $row->toArray();
                // only add unique value to $allResults
                if (!in_array($rowArray, $allResults)) {
                    $allResults[] = $rowArray;
                }

            }

        }
        
        //var_dump($allResults); exit();
        return $allResults;
        
    }
    
    public
    function getTotalReward()
    {
       
        $employee = Yii::$app->user->identity->employee;
        $people = Employee::find()->where(['nik' => $employee->nik])->one(); // test Employee doang, ganti pakai yg di atas


        $baseReward = MstReward::findAll(['status' => MstReward::ACTIVE]);

        //$group = [];
        $allResults = [];
        // loop through all kind of reward
        foreach ($baseReward as $item) {
            $baseQuery = Reward::find()
                ->select('mst_reward_id')
                ->where(['isApproved' => Reward::APPROVED])
                ->andFilterWhere(['mst_reward_id' => $item->id]);

            // get all criteria_name for this reward
            $criteriaNamesQ = RewardCriteria::find()
                ->select('criteria_name')
                ->where(['mst_reward_id' => $item->id])
                ->asArray()
                ->all();

            $criteriaNames = [];
            foreach ($criteriaNamesQ as $cn) {
                $criteriaNames[] = $cn['criteria_name'];
            }

            if (count($criteriaNames) > 0) {
                // run if there's rule in table reward_criteria
                if (in_array('band_individu', $criteriaNames)) {
                    $baseQuery->andWhere(['band_individu' => $people->bi]);
                }
                if (in_array('marital_status', $criteriaNames)) {
                    $baseQuery->andWhere(['marital_status' => $people->status_pernikahan]);
                }
                if (in_array('structural', $criteriaNames)) {
                    $baseQuery->andWhere(['structural' => $people->structural]);
                }
                if (in_array('functional', $criteriaNames)) {
                    $baseQuery->andWhere(['functional' => $people->functional]);
                }
                if (in_array('band_position', $criteriaNames)) {
                    $baseQuery->andWhere(['band_position' => $people->bp]);
                }
                if (in_array('emp_category', $criteriaNames)) {
                    $baseQuery->andWhere(['emp_category' => $people->employee_category]);
                }
                if (in_array('gender', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->gender]);
                }
                if (in_array('kota', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->kota]);
                }
            } else {
                // no rules.. default filter by band_individu
                $baseQuery->andWhere(['band_individu' => $people->bi]);
            }


            $thisResult = $baseQuery->all();

            foreach ($thisResult as $row) {
                $theRewardName = \reward\models\MstReward::find()
                    ->select(['reward_name', 'icon'])
                    ->where(['id' => $row['mst_reward_id']])
                    ->all();

                foreach ($theRewardName as $rows) {
                    $rowArray = $rows->toArray();
                    // only add unique value to $allResults
                    if (!in_array($rowArray, $allResults)) {
                        $allResults[] = $rowArray;

                    }
                }
            }


//            foreach ($thisResult as $item) {
//                //$key = $item['mst_reward_id'];
//
//                $theRewardName = \reward\models\MstReward::find()
//                    ->select(['id', 'reward_name', 'categoryId'])
//                    ->where(['id' => $item['mst_reward_id']])->all();
//
//                foreach ($theRewardName as $rows) {
//                    $theCatName = \reward\models\Category::find()
//                        ->select(['id', 'category_name', 'category_type_id'])
//                        ->where(['id' => $rows['categoryId']])->all();
//
//                    $key = $rows['categoryId'];
//
//                    foreach ($theCatName as $row) {
//                        //$key = $row['category_type_id'];
//                        if (!array_key_exists($key, $group)) {
//                            $group[$key] = array(
//                                'id' => $row['id'],
//                                'category_name' => $row['category_name'],
//                                'amount' => $item['amount'],
//                                'count' => 1,
//                            );
//                        } else {
//                            //$group[$key]['items'][] = $item;
//                            $group[$key]['amount'] += $item['amount'];
//                            $group[$key]['count'] += 1;
//                        }
//                    }
//
//                }
//
//
//            }


        }

        return $allResults;

    }

    public
    function getRewardModal()
    {
        $employee = Yii::$app->user->identity->employee;
        $people = Employee::find()->where(['nik' => $employee->nik])->one(); // test Employee doang, ganti pakai yg di atas


        $baseReward = MstReward::findAll(['status' => MstReward::ACTIVE]);

        //$group = [];
        $allResults = [];
        // loop through all kind of reward
        foreach ($baseReward as $item) {
            $baseQuery = Reward::find()
                ->where(['isApproved' => Reward::APPROVED])
                ->andFilterWhere(['mst_reward_id' => $item->id]);

            // get all criteria_name for this reward
            $criteriaNamesQ = RewardCriteria::find()
                ->select('criteria_name')
                ->where(['mst_reward_id' => $item->id])
                ->asArray()
                ->all();

            $criteriaNames = [];
            foreach ($criteriaNamesQ as $cn) {
                $criteriaNames[] = $cn['criteria_name'];
            }

            if (count($criteriaNames) > 0) {
                // run if there's rule in table reward_criteria
                if (in_array('band_individu', $criteriaNames)) {
                    $baseQuery->andWhere(['band_individu' => $people->bi]);
                }
                if (in_array('marital_status', $criteriaNames)) {
                    $baseQuery->andWhere(['marital_status' => $people->status_pernikahan]);
                }
                if (in_array('structural', $criteriaNames)) {
                    $baseQuery->andWhere(['structural' => $people->structural]);
                }
                if (in_array('functional', $criteriaNames)) {
                    $baseQuery->andWhere(['functional' => $people->functional]);
                }
                if (in_array('band_position', $criteriaNames)) {
                    $baseQuery->andWhere(['band_position' => $people->bp]);
                }
                if (in_array('emp_category', $criteriaNames)) {
                    $baseQuery->andWhere(['emp_category' => $people->employee_category]);
                }
                if (in_array('gender', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->gender]);
                }
                if (in_array('kota', $criteriaNames)) {
                    $baseQuery->andWhere(['gender' => $people->kota]);
                }
            } else {
                // no rules.. default filter by band_individu
                $baseQuery->andWhere(['band_individu' => $people->bi]);
            }


            $thisResult = $baseQuery->all();

            foreach ($thisResult as $row) {

                $rowArray = $row->toArray();
                // only add unique value to $allResults
                if (!in_array($rowArray, $allResults)) {
                    $allResults[] = $rowArray;

                }
            }

        }

        return $allResults;

    }

    public
    function getRewards()
    {
        $model = Reward::findAll(['status' => Reward::ACTIVE]);

        return $model;
    }


    public
    function getUser()
    {
        return $this->hasOne(User::className(), ['nik' => 'nik']);
    }


    public
    function getIsAdmin()
    {
        $userRoles = Yii::$app->authManager->getRolesByUser($this->user->id);
        $this->_isAdmin = false;

        foreach ($userRoles as $userRole) {
            if ('reward_admin' == $userRole->name) {
                $this->_isAdmin = true;
                break;
            }
        }

        return $this->_isAdmin;
    }

    public
    function getIsAdminProjection()
    {
        $userRoles = Yii::$app->authManager->getRolesByUser($this->user->id);
        $this->_isAdminProjection = false;

        foreach ($userRoles as $userRole) {
            if ('reward_projection' == $userRole->name) {
                $this->_isAdminProjection = true;
                break;
            }
        }

        return $this->_isAdminProjection;
    }

    public
    function getCareerPath($startDate, $endDate)
    {
        $dates = Helpers::getMonthIterator($startDate, $endDate);

        //get value from setting model
        $asumsiPoint = floatval(Setting::getBaseSetting(Setting::INDEX_ASUMSI_POINT));
        $totalPoint = floatval(Setting::getBaseSetting(Setting::INDEX_TOTAL_POINT));

        $careerPath = [];
        $startDateTime = new \DateTime($startDate);
        $startBi = $this->bi;
        $bandBi = 0;
        $bandBp = 0;
        if ($this->bi) $bandBi = intval(substr($this->bi, 0, 1));
        if ($this->bp) $bandBp = intval(substr($this->bp, 0, 1));


        if ('TRAINEE' == $this->employee_category) {
            $careerPath['type'] = 'trainee';


            // check dpe thd tgl mulai simulasi
            $dpeDateTime = new \DateTime($this->dpe);

            if ($startDateTime > $dpeDateTime) {
                $startBi = '1.a'; // sudah nggak trainee lagi, pakai level terendah
            }

            $startDateTimeFmt = $startDateTime->format('Ym');

            $path = [];

            $path[$startDateTimeFmt]['bi'] = $startBi;
            $path[$startDateTimeFmt]['is_naik_bi'] = false;
            $path[$startDateTimeFmt]['saldo_nki'] = 0;

            $it = 0;
            $prevSemester = 0;
            $prevSaldoNki = null;
            /**
             * @var $date \DateTime
             */
            foreach ($dates as $date) {

//                var_dump($date);

                // skip first
                if (0 == $it) {
                    $it++;
                    continue;
                }

//                echo $it.'.';
                $it++;

                $prevDate = clone $date;
                $prevDate = $prevDate->modify('-1 month');

                $currentSemester = ceil(intval($date->format('m')) / 6);  // bulan ini semester berapa?

                $theSaldoNki = null;
                $dateFmt = $date->format('Ym');
                $prevDateFmt = $prevDate->format('Ym');


                if (intval($dateFmt) > intval($dpeDateTime->format('Ym'))) {
                    // sudah jadi normal Employee --> pakai saldo_nki
                    if ($currentSemester != $prevSemester) {
                        // ganti semester, check tabel saldo_nki
                        $prevSemester = $currentSemester;

                        $theSaldoNki = SaldoNki::find()->where([
                            'nik' => $this->nik,
                            'tahun' => $date->format('Y'),
                        ])
                            ->andWhere(['smt' => $currentSemester])
                            ->orderBy(['smt' => SORT_DESC])
                            ->one();

                        if ($theSaldoNki) {
                            // ada saldo nki untuk semester dan tahun ini
                            $path[$dateFmt]['bi'] = $theSaldoNki->bi;
                            $path[$dateFmt]['is_naik_bi'] = false;
                            $path[$dateFmt]['saldo_nki'] = $theSaldoNki->total;
                            $prevSaldoNki = $theSaldoNki;

                        } else {
                            $theSaldoNki = $prevSaldoNki;

                            $path[$dateFmt]['bi'] = $path[$prevDateFmt]['bi'];
                            $path[$dateFmt]['is_naik_bi'] = false;
                            $path[$dateFmt]['saldo_nki'] = $path[$prevDateFmt]['saldo_nki'] + $asumsiPoint;
                        }

                        // check naik bi
                        if ($path[$dateFmt]['saldo_nki'] >= $totalPoint) {
                            $path[$dateFmt]['bi'] = Helpers::nextBand($path[$dateFmt]['bi']);
                            $path[$dateFmt]['is_naik_bi'] = true;
                            $path[$dateFmt]['caused_by'] = 'KENAIKAN SALDO NKI';
                            $path[$dateFmt]['nik'] = $this->nik;
                            $path[$dateFmt]['new_bi_band_1'] = 1;
                            $path[$dateFmt]['saldo_nki'] = 0;
                        }
                    } else {
                        if (!$path[$prevDateFmt]['bi']) {
                            $path[$dateFmt]['bi'] = '1.a';
                            $path[$dateFmt]['is_naik_bi'] = true;
                            $path[$dateFmt]['caused_by'] = 'KENAIKAN TRAINEE KE 1.a';
                            $path[$dateFmt]['nik'] = $this->nik;
                            $path[$dateFmt]['new_bi_band_1'] = 1;
                            $path[$dateFmt]['saldo_nki'] = 0;
                        } else {
                            $path[$dateFmt]['bi'] = $path[$prevDateFmt]['bi'];
                            $path[$dateFmt]['is_naik_bi'] = false;
                            $path[$dateFmt]['saldo_nki'] = $path[$prevDateFmt]['saldo_nki'];
                        }

                    }
                } else {
                    // masih trainee
                    $path[$dateFmt]['bi'] = $startBi;
                    $path[$dateFmt]['is_naik_bi'] = false;
                    $path[$dateFmt]['saldo_nki'] = 0;

                    if ($currentSemester != $prevSemester) {
                        $prevSemester = $currentSemester;
                    }
                }
            }

            $careerPath['path'] = $path;

        } elseif (($bandBp > $bandBi) && ($this->bi)) {
            // karyawan evaluasi
            $careerPath['type'] = 'evaluasi';

            $dpeDateTime = new \DateTime($this->dpe);
//            $dpeDateTime->modify('+6 months');
            $prevSaldoNki = null;
            $path = [];
            /**
             * @var $date \DateTime
             */
            foreach ($dates as $date) {
                $prevDate = clone $date;
                $prevDate = $prevDate->modify('-1 month');

                $theSaldoNki = null;
                $dateFmt = $date->format('Ym');
                $prevDateFmt = $prevDate->format('Ym');


                if (($this->dpe) && (intval($dateFmt) > intval($dpeDateTime->format('Ym')))) {
                    // naik BI
                    if ($path) {
                        // check if the target BP is already reached
                        if ($path[$prevDateFmt]['bi'] != $this->bp) {
                            // not yet reached
                            if ($path[$prevDateFmt]['bi'] == $this->bi) {
                                // loncat band
                                $nextBi = substr($this->bp, 0, 1) . '.1';
                            } else {
                                $nextBi = Helpers::nextBand($path[$prevDateFmt]['bi']);
                            }
                        } else {
                            // stop increasing the bi
                            $nextBi = $path[$prevDateFmt]['bi'];
                        }

                    } elseif ($path[$prevDateFmt]['bi'] == $this->bi) {
                        // loncat band
                        $nextBi = substr($this->bp, 0, 1) . '.1';
                    }

                    $path[$dateFmt]['bi'] = $nextBi;
                    $path[$dateFmt]['is_naik_bi'] = true;
                    $path[$dateFmt]['caused_by'] = 'EVALUASI';
                    $path[$dateFmt]['nik'] = $this->nik;
                    $path[$dateFmt]['new_bi_band_others'] = 1;
                    $path[$dateFmt]['saldo_nki'] = 0;

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
                    $path[$dateFmt]['saldo_nki'] = 0;
                }
            }

            $careerPath['path'] = $path;
        } elseif (($bandBp < $bandBi) && ($this->bi)) {
            // turun band
            $careerPath['type'] = 'downgrade';

            $startDateAssignment = new \DateTime($this->start_date_assignment);
            $prevSaldoNki = null;
            $path = [];
            /**
             * @var $date \DateTime
             */
            foreach ($dates as $date) {
                $prevDate = clone $date;
                $prevDate = $prevDate->modify('-1 month');

                $theSaldoNki = null;
                $dateFmt = $date->format('Ym');
                $prevDateFmt = $prevDate->format('Ym');
                if ((intval($dateFmt) >= intval($startDateAssignment->format('Ym')))) {
                    // turun Band
//                    if ('1' == $this->bp) {
//                        $path[$dateFmt]['bi'] = '1.j';
//                    } else {
//                        $path[$dateFmt]['bi'] = $this->bi;
//                    }

                    $path[$dateFmt]['bi'] = $this->bi;
                    $path[$dateFmt]['is_naik_bi'] = false;
                    $path[$dateFmt]['saldo_nki'] = 0;
                } else {
                    // masih pakai bi yg lama
                    $path[$dateFmt]['bi'] = $this->bi;
                    $path[$dateFmt]['is_naik_bi'] = false;
                    $path[$dateFmt]['saldo_nki'] = 0;
                }
            }

            $careerPath['path'] = $path;
        } elseif (1 == $bandBi) {
            $careerPath['type'] = 'normal band 1';

            $startDateTimeFmt = $startDateTime->format('Ym');

            $currentSemester = ceil(intval($startDateTime->format('m')) / 6);  // bulan ini semester berapa?
            $currentYear = intval($startDateTime->format('Y'));

            $theSaldoNki = SaldoNki::find()->where([
                'nik' => $this->nik,
            ])
                ->andWhere(['smt' => $currentSemester])
                ->andWhere(['tahun' => $currentYear])
                ->orderBy(['smt' => SORT_DESC])
                ->one();

            $path = [];

            $path[$startDateTimeFmt]['bi'] = $startBi;
            $path[$startDateTimeFmt]['is_naik_bi'] = false;
            if ($theSaldoNki) {
                $path[$startDateTimeFmt]['saldo_nki'] = floatval($theSaldoNki->total);
                $path[$startDateTimeFmt]['from_saldo_nki'] = true;
                $prevSaldoNki = $theSaldoNki;
            } else {
                $path[$startDateTimeFmt]['saldo_nki'] = 0; // nggak ada di tabel saldo_nki
                $path[$startDateTimeFmt]['from_saldo_nki'] = false;
                $prevSaldoNki = null;
            }


            $it = 0;
            $prevSemester = $currentSemester;
            $prevYear = $currentYear;

            /**
             * @var $date \DateTime
             */
            foreach ($dates as $date) {
                if (0 == $it) {
                    // skip first
                    $it++;
                    continue;
                }
                $it++;

                $prevDate = clone $date;
                $prevDate = $prevDate->modify('-1 month');

                $currentSemester = ceil(intval($date->format('m')) / 6);  // bulan ini semester berapa?
                $currentYear = intval($date->format('Y'));

                $theSaldoNki = null;
                $dateFmt = $date->format('Ym');
                $prevDateFmt = $prevDate->format('Ym');


                if ($currentSemester != $prevSemester) {

                    // ganti semester, check tabel saldo_nki
                    $prevSemester = $currentSemester;

                    $theSaldoNki = SaldoNki::find()->where([
                        'nik' => $this->nik,
                        'tahun' => $date->format('Y'),
                    ])
                        ->andWhere(['smt' => $currentSemester])
                        ->andWhere(['tahun' => $currentYear])
                        ->orderBy(['smt' => SORT_DESC])
                        ->one();


                    if ($theSaldoNki) {
                        // ada saldo nki untuk semester dan tahun ini
                        $path[$dateFmt]['bi'] = $theSaldoNki->bi;
                        $path[$dateFmt]['is_naik_bi'] = false;
                        $path[$dateFmt]['saldo_nki'] = $theSaldoNki->total;
                        $path[$dateFmt]['from_saldo_nki'] = true;

                        $prevSaldoNki = $theSaldoNki;

                    } else {
                        // nggak ada di saldo_nki
                        $theSaldoNki = $prevSaldoNki;

                        if ($path[$prevDateFmt]) {
                            $path[$dateFmt]['bi'] = $path[$prevDateFmt]['bi'];
                            $path[$dateFmt]['is_naik_bi'] = false;
                            $path[$dateFmt]['saldo_nki'] = $path[$prevDateFmt]['saldo_nki'] + $asumsiPoint; // naikin +3 by default
                            $path[$dateFmt]['from_saldo_nki'] = false;

                        } else {
                            // first hit on semester changes
                            $path[$dateFmt]['bi'] = $startBi;
                            $path[$dateFmt]['is_naik_bi'] = false;

                            if ($theSaldoNki) {
                                $path[$dateFmt]['saldo_nki'] = $theSaldoNki->total;
                                $path[$dateFmt]['from_saldo_nki'] = true;
                            } else {
                                $path[$dateFmt]['saldo_nki'] = 0;
                                $path[$dateFmt]['from_saldo_nki'] = false;
                            }

                        }

                    }

                    // check naik bi
                    if ($path[$dateFmt]['saldo_nki'] >= $totalPoint) {
                        $path[$dateFmt]['bi'] = Helpers::nextBand($path[$dateFmt]['bi']);
                        $path[$dateFmt]['is_naik_bi'] = true;
                        $path[$dateFmt]['caused_by'] = 'KENAIKAN SALDO NKI';
                        $path[$dateFmt]['nik'] = $this->nik;
                        $path[$dateFmt]['new_bi_band_1'] = 1;
                        $path[$dateFmt]['saldo_nki'] = 0;
                        $path[$dateFmt]['from_saldo_nki'] = true;
                    }
                } else {
                    $path[$dateFmt]['bi'] = $path[$prevDateFmt]['bi'];
                    $path[$dateFmt]['is_naik_bi'] = false;
                    $path[$dateFmt]['saldo_nki'] = $path[$prevDateFmt]['saldo_nki'];
                    $path[$dateFmt]['from_saldo_nki'] = false;
                }
            }

            $careerPath['path'] = $path;
        } elseif ($bandBi > 1) {
            $careerPath['type'] = 'normal band > 1';

            $path = [];
            /**
             * @var $date \DateTime
             */
            foreach ($dates as $date) {
                $dateFmt = $date->format('Ym');
                $path[$dateFmt]['bi'] = $startBi;
                $path[$dateFmt]['is_naik_bi'] = false;
                $path[$dateFmt]['saldo_nki'] = floatval($this->score);
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


}

?>