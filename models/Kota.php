<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "kota".
 *
 * @property int $id
 * @property string $kota
 * @property string $created_at
 * @property string $updated_at
 */
class Kota extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kota';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kota'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['kota'], 'string', 'max' => 50],
            [['kota'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kota' => 'Kota',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
