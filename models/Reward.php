<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "reward".
 *
 * @property string $id
 * @property string $emp_category
 * @property int $band
 * @property string $band_individu
 * @property string $band_position
 * @property string $structural
 * @property string $functional
 * @property string $marital_status
 * @property string $gender
 * @property string $organization
 * @property string $job
 * @property string $location
 * @property string $kota
 * @property string $department
 * @property string $division
 * @property string $homebase
 * @property double $amount
 * @property string $mst_reward_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property MstReward $mstReward
 */
class Reward extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const ACTIVE = 1;
    const INACTIVE = 0;

    const PENDING = -1;
    const APPROVED = 1;
    const REJECTED = 0;

    public static function tableName()
    {
        return 'reward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['band', 'mst_reward_id', 'isApproved'], 'integer'],
            [['amount', 'mst_reward_id'], 'required'],
            //[['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['emp_category', 'organization', 'job', 'location', 'kota', 'department', 'division', 'homebase'], 'string', 'max' => 50],
            [['band_individu', 'band_position'], 'string', 'max' => 5],
            [['structural', 'functional'], 'string', 'max' => 1],
            [['marital_status', 'gender'], 'string', 'max' => 10],
            [['mst_reward_id'], 'exist', 'skipOnError' => true, 'targetClass' => MstReward::className(), 'targetAttribute' => ['mst_reward_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emp_category' => 'Employee Category',
            'band' => 'Band',
            'band_individu' => 'Band Individu',
            'band_position' => 'Band Position',
            'structural' => 'Structural',
            'functional' => 'Functional',
            'marital_status' => 'Marital Status',
            'gender' => 'Gender',
            'organization' => 'Organization',
            'job' => 'Job',
            'location' => 'Location',
            'kota' => 'Kota',
            'department' => 'Department',
            'division' => 'Division',
            'homebase' => 'Homebase',
            'amount' => 'Amount',
            'mst_reward_id' => 'Mst Reward ID',
            'isApproved' => 'Approved',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMstReward()
    {
        return $this->hasOne(MstReward::className(), ['id' => 'mst_reward_id']);
    }

    public function beforeSave($insert)
    {

        if($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");

        $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);

    }
}
