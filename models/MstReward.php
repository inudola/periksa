<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "mst_reward".
 *
 * @property string $id
 * @property string $reward_name
 * @property string $status
 * @property string $categoryId
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 * @property Reward[] $rewards
 * @property mixed|UploadedFile icon
 * @property UploadedFile file
 */
class MstReward extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const ACTIVE = 1;
    const INACTIVE = 0;


    public static function tableName()
    {
        return 'mst_reward';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reward_name', 'categoryId'], 'required'],
            [['status', 'description', 'formula'], 'string'],
            [['categoryId'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['reward_name'], 'string', 'max' => 50],
            [['reward_name'], 'unique'],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf', 'maxSize' => 1028000, 'tooBig' => 'Limit is 1000KB'],
            [['icon'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg', 'maxSize'=>1024 * 1024 * 1],
            [['icon'], 'required', 'on'=> 'create'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['categoryId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reward_name' => 'Reward Name',
            'status' => 'Status',
            'description' => 'Description',
            'categoryId' => 'Category',
            'file' => 'File (Pdf)',
            'icon' => 'Icon',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRewards()
    {
        return $this->hasMany(Reward::className(), ['mst_reward_id' => 'id']);
    }

    public function getRewardCriterias()
    {
        return $this->hasMany(RewardCriteria::className(), ['mst_reward_id' => 'id']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {

                $this->created_at = date("Y-m-d H:i:s");
            } else {
                $this->updated_at = date("Y-m-d H:i:s");
            }
            return true;

        } else {
            return false;
        }
    }
}


