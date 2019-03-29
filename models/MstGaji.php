<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "mst_gaji".
 *
 * @property int $id
 * @property string $bi
 * @property int $gaji_dasar
 * @property int $tunjangan_biaya_hidup
 * @property int $tunjangan_jabatan_struktural
 * @property int $tunjangan_jabatan_functional
 * @property int $tunjangan_rekomposisi
 * @property string $created_at
 * @property string $updated_at
 */
class MstGaji extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function primaryKey()
    {
        return ["id"];
    }

    public static function tableName()
    {
        return 'mst_gaji';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['gaji_dasar', 'tunjangan_biaya_hidup', 'tunjangan_jabatan_struktural', 'tunjangan_jabatan_functional', 'tunjangan_rekomposisi'], 'integer'],
            [['bi'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bi' => 'Band Individu',
            'gaji_dasar' => 'Gaji Dasar',
            'tunjangan_biaya_hidup' => 'Tunjangan Biaya Hidup',
            'tunjangan_jabatan_struktural' => 'Tunjangan Jabatan Struktural',
            'tunjangan_jabatan_functional' => 'Tunjangan Jabatan Functional',
            'tunjangan_rekomposisi' => 'Tunjangan Rekomposisi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
