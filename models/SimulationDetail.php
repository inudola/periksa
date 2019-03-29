<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "simulation_detail".
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
 * @property mixed parent_id
 * @property mixed keterangan
 * @property mixed updated_by
 * @property mixed n_group
 */
class SimulationDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $cnt;
    public $my_sum;
    public $sumReal;
    public $sumProj;

    const GADAS = 'GAJI DASAR';
    const TBH   = 'TBH';
    const TUNJANGAN_REKOMPOSISI = 'TUNJANGAN REKOMPOSISI';

    public static function tableName()
    {
        return 'simulation_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['simulation_id', 'bulan', 'tahun', 'amount'], 'required'],
            [['simulation_id', 'bulan', 'tahun', 'batch_id', 'n_group'], 'integer'],
            //[['amount'], 'number'],
            [['created_at', 'updated_at', 'updated_by', 'created_by'], 'safe'],
            [['element', 'keterangan'], 'string', 'max' => 64],
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
            'n_group' => 'Personnel Expense',
            'amount' => 'Amount',
            'batch_id' => 'Type Batch',
            'keterangan' => 'Keterangan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'my_sum' => 'Total',
            'sumProj' => 'Total Projection',
            'sumReal' => 'Total Realization'
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

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord)
                $this->created_at = date("Y-m-d H:i:s");

            else
                $this->updated_at = date("Y-m-d H:i:s");

            return true;
        } else {
            return false;
        }
    }

    public function getBulan()
    {
        $data = [

            '0' => '1',
            '1' => '2',
            '2' => '3',
            '3' => '4',
            '4' => '5',
            '5' => '6',
            '6' => '7',
            '7' => '8',
            '8' => '9',
            '9' => '10',
            '10' => '11',
            '11' => '12'
        ];

        return $data;
    }


    public function GetMonth()
    {

        $model = SimulationDetail::findOne($this->id);

        if ($model->bulan == 1) {
            return 'January';
        }
        if ($model->bulan == 2) {
            return 'February';
        }
        if ($model->bulan == 3) {
            return 'March';
        }
        if ($model->bulan == 4) {
            return 'April';
        }
        if ($model->bulan == 5) {
            return 'May';
        }
        if ($model->bulan == 6) {
            return 'June';
        }
        if ($model->bulan == 7) {
            return 'July';
        }
        if ($model->bulan == 8) {
            return 'August';
        }
        if ($model->bulan == 9) {
            return 'September';
        }
        if ($model->bulan == 10) {
            return 'October';
        }
        if ($model->bulan == 11) {
            return 'November';
        }
        if ($model->bulan == 12) {
            return 'December';
        } else {
            return "No Month Data";
        }

        return $model;
    }


    public function getPayroll()
    {
        return $this->hasOne(PayrollResult::className(), ['period_bulan' => 'bulan']);
    }

    public function getMstNature()
    {
        return $this->hasOne(MstNature::className(), ['id' => 'n_group']);
    }

}
