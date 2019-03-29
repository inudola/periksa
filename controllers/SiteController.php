<?php

namespace reward\controllers;

use common\models\LoginForm;
use DateTime;
use reward\models\MstReward;
use reward\models\PayrollResultOci;
use reward\models\Reward;
use reward\models\Setting;
use reward\models\Simulation;
use reward\models\Category;
use reward\models\Employee;
use reward\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['logout'],
                'rules' => [
                    [
//                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['add-admin'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            // check if admin
            $isAdmin = Yii::$app->user->identity->employee->isAdmin;
            Yii::$app->session->set('isAdmin', $isAdmin);

            //check if admin projection
            $isAdminProjection = Yii::$app->user->identity->employee->isAdminProjection;
            Yii::$app->session->set('isAdminProjection', $isAdminProjection);

            $isGuest = Yii::$app->user->isGuest;
            Yii::$app->session->set('isGuest', $isGuest);
        }

        $model = Employee::instance()->getReward();

        $people = Yii::$app->user->identity->employee;
        $basedata = $people->salary + $people->tunjangan + $people->tunjangan_rekomposisi;
        $basedataFull = $people->salary + $people->tunjangan + $people->tunjangan_jabatan + $people->tunjangan_rekomposisi;

        $result['mst_reward'] = MstReward::find()->count();
        $result['category'] = Category::find()->count();
        $result['simulation'] = Simulation::find()->count();

        //get masa kerja
        $tahunMasuk = date("Y-m", strtotime($people->tanggal_masuk));
        $currentYear = date("Y-m");
        $diff = (new DateTime($tahunMasuk))->diff(new DateTime($currentYear));
        $masaKerja = $diff->m + $diff->y * 12;

        //get value from setting model
        $indexTHR = floatval(Setting::getBaseSetting(Setting::INDEX_THR_1));
        $indexTHR2 = floatval(Setting::getBaseSetting(Setting::INDEX_THR_2));
        $indexTunjCuti = floatval(Setting::getBaseSetting(Setting::INDEX_TUNJANGAN_CUTI));

        //value PMK Structure
        $indexPMK5 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_5));
        $indexPMK10 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_10));
        $indexPMK15 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_15));
        $indexPMK20 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_20));
        $indexPMK25 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_25));
        $indexPMK30 = floatval(Setting::getBaseSetting(Setting::INDEX_PMK_30));

        $indexCutiBesar = floatval(Setting::getBaseSetting(Setting::INDEX_CUTI_BESAR));

        return $this->render('index', [
            'result' => $result,
            'model' => $model,
            'indexTHR' => $indexTHR,
            'indexTHR2' => $indexTHR2,
            'indexTunjCuti' =>$indexTunjCuti,
            'masaKerja' => $masaKerja,
            'indexPMK5' => $indexPMK5,
            'indexPMK10' => $indexPMK10,
            'indexPMK15' => $indexPMK15,
            'indexPMK20' => $indexPMK20,
            'indexPMK25' => $indexPMK25,
            'indexPMK30' => $indexPMK30,
            'indexCutiBesar' => $indexCutiBesar,
            'basedata' => $basedata,
            'basedataFull' => $basedataFull,
        ]);
    }


    public function actionRewardDetail($id)
    {
        //$this->layout = false;

        $model = Employee::instance()->getRewardDetail($id);

        return $this->renderAjax('reward-modal', [
            'model' => $model
        ]);
    }


    public function actionCategory($id)
    {
        $this->layout = false;
        //$model1 = Category::findOne($id);

        $model1 = Employee::instance()->getCategory($id);

        return $this->render('reward-category', [
            'model1' => $model1,
        ]);

    }

    public function actionRewards($id)
    {
        $this->layout = false;
        $model1 = Category::findOne($id);

        if ($id == 2) {
            return $this->render('rewards-thp');
        } else {
            $rewards = $model1->rewards;

            return $this->render('rewards', [
                'model1' => $model1,
                'rewards' => $rewards,
            ]);
        }
    }

    public function actionTotalReward()
    {
        //$this->layout = false;
        $model1 = new Employee();


        $rewards = $model1->totalReward;

        return $this->render('total-reward', [
            'model1' => $model1,
            'rewards' => $rewards,
        ]);

    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                return $this->goBack();
            }

        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionOptions()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $totalReward = Employee::instance()->getTotalReward();

        return $this->asJson($totalReward);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAddAdmin()
    {
        $model = User::find()->where(['username' => 'admin'])->one();
        if (empty($model)) {
            $user = new User();
            $user->id = 'admin';
            $user->username = 'admin';
            $user->email = 'admin@devreadwrite.com';
            $user->setPassword('admin');
            $user->generateAuthKey();
            if ($user->save()) {
                echo 'good';
            }
        } else {
            echo 'already exist';
        }
    }


    public function actionTestOci()
    {
        return PayrollResultOci::getTestTable();
    }

}
