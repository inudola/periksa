<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "mst_nature".
 *
 * @property string $id
 * @property string $nature_code
 * @property string $nature_name
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class MstNature extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_nature';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nature_name'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nature_code'], 'string', 'max' => 20],
            [['nature_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nature_code' => 'Nature Code',
            'nature_name' => 'Nature Name',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getSimulationDetail()
    {
        return $this->hasMany(SimulationDetail::className(), ['n_group' => 'id']);
    }

    public function getSettings()
    {
        return $this->hasMany(Setting::className(), ['group_nature' => 'id']);
    }
}
