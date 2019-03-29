<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "mst_batch".
 *
 * @property int $id
 * @property int $type
 * @property string $isYear
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BatchEntry[] $batchEntries
 */
class MstType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const YEAR = 'Y';
    const MONTH = 'N';

    public static function tableName()
    {
        return 'mst_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'isYear'], 'required'],
            [['isYear', 'type'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'isYear' => 'Type Projection',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBatchEntries()
    {
        return $this->hasMany(BatchEntry::className(), ['type_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->type = strtoupper($this->type);

        if($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");
        else
            $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);
    }
}
