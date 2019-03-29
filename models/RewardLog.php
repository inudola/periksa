<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "reward_log".
 *
 * @property int $id
 * @property string $user
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class RewardLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reward_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'description', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['user', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    //=== function save to logging start ===//
    public static function saveLog($user, $action){
        //logging save data
        $logging = new RewardLog();
        $logging->user = $user;
        $logging->description = $action;
        $logging->created_at = date("Y-m-d H:i:s");
        $logging->updated_at = date("Y-m-d H:i:s");
        $logging->save();
    }
    //=== function save to logging end ===//
}
