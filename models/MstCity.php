<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "mst_city".
 *
 * @property string $code
 * @property string $name
 * @property double $idx_tbh
 */
class MstCity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mst_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['idx_tbh'], 'number'],
            [['code'], 'string', 'max' => 16],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'name' => 'Name',
            'idx_tbh' => 'Idx Tbh',
        ];
    }
}
