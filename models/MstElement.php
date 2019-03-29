<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "mst_element".
 *
 * @property int $id
 * @property string $element_name
 * @property string $isYear
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ElementDetail[] $elementDetails
 */
class MstElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    const ACTIVE = 1;
    const INACTIVE =0;
    const YEAR = 'Y';
    const MONTH = 'N';

    public static function tableName()
    {
        return 'mst_element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['element_name', 'isYear'], 'required'],
            [['isYear', 'status', 'type'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['element_name'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'element_name' => 'Element Name',
            'isYear' => 'Element Type',
            'status' => 'Status',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementDetails()
    {
        return $this->hasMany(ElementDetail::className(), ['mst_element_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        $this->element_name = strtoupper($this->element_name);

        if($this->isNewRecord)
            $this->created_at = date("Y-m-d H:i:s");
        else
            $this->updated_at = date("Y-m-d H:i:s");

        return parent::beforeSave($insert);
    }

    public static function getOptionsbyElement($cat_id) {
        $data = static::find()->where(['type'=>$cat_id])->select(['id','element_name AS name'])->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }
}
