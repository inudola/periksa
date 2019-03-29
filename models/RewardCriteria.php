<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "reward_criteria".
 *
 * @property int $id
 * @property string $criteria_name
 * @property string $mst_reward_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property MstReward $mstReward
 */
class RewardCriteria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward_criteria';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['criteria_name', 'mst_reward_id'], 'required'],
            [['mst_reward_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            //[['criteria_name'], 'string', 'max' => 30],
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
            'criteria_name' => 'Criteria Name',
            'mst_reward_id' => 'Mst Reward ID',
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

        if ($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");

        else
            $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);
    }
}
