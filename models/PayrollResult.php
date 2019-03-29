<?php

namespace reward\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "payroll_result".
 *
 * @property int $id
 * @property string $payroll_name
 * @property string $period_bulan
 * @property string $period_tahun
 * @property int $curr_amount
 *@property mixed element_name
 * @property string resource
 */
class PayrollResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $cnt;
    public $sumReal;

    public static function tableName()
    {
        return 'payroll_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['curr_amount', 'period_bulan', 'period_tahun', 'curr_amount'], 'integer'],
            [['payroll_name', 'resource'], 'string', 'max' => 20],
            [['element_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payroll_name' => 'Payroll Name',
            'period_bulan' => 'Bulan',
            'period_tahun' => 'Tahun',
            'element_name' => 'Element Name',
            'curr_amount' => 'Amount',
            'resource' => 'Resource',
            'sumReal' => 'Total Realization'
        ];
    }

    public static function getList()
    {
        $droptions = PayrollResult::find()->asArray()->orderBy(['id' => SORT_DESC])->all();
        return ArrayHelper::map($droptions, 'resource', function ($model) {
            return $model['resource'];
        });
    }

    public function GetMonth()
    {

        $model = PayrollResult::findOne($this->id);

        if ($model->period_bulan == 1) {
            return 'January';
        }
        if ($model->period_bulan == 2) {
            return 'February';
        }
        if ($model->period_bulan == 3) {
            return 'March';
        }
        if ($model->period_bulan == 4) {
            return 'April';
        }
        if ($model->period_bulan == 5) {
            return 'May';
        }
        if ($model->period_bulan == 6) {
            return 'June';
        }
        if ($model->period_bulan == 7) {
            return 'July';
        }
        if ($model->period_bulan == 8) {
            return 'August';
        }
        if ($model->period_bulan == 9) {
            return 'September';
        }
        if ($model->period_bulan == 10) {
            return 'October';
        }
        if ($model->period_bulan == 11) {
            return 'November';
        }
        if ($model->period_bulan == 12) {
            return 'December';
        } else {
            return "No Month Data";
        }

        return $model;
    }

    public function beforeSave($insert)
    {
        $this->element_name = strtoupper($this->element_name);

        if ($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");

        else
            $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);
    }

    public function setTruncateData()
    {
        Yii::$app->db->createCommand()->truncateTable('payroll_result')->execute();
    }
}
