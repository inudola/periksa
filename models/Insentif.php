<?php

namespace reward\models;

use Mpdf\Tag\Ins;
use Yii;

/**
 * This is the model class for table "insentif".
 *
 * @property int $id
 * @property string $nik
 * @property string $bi
 * @property string $band
 * @property int $smt
 * @property int $tahun
 * @property string $nki
 * @property string $nku
 * @property string $organisasi_nku
 * @property string $tipe_organisasi
 * @property string $created_at
 * @property string $updated_at
 */
class Insentif extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insentif';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smt', 'tahun'], 'integer'],
            [['nki', 'nku', 'nkk'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['nik', 'bi', 'band'], 'string', 'max' => 10],
            [['organisasi_nku', 'tipe_organisasi'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nik' => 'Nik',
            'bi' => 'Bi',
            'band' => 'Band',
            'smt' => 'Smt',
            'tahun' => 'Tahun',
            'nki' => 'Nki',
            'nku' => 'Nku',
            'organisasi_nku' => 'Organisasi Nku',
            'tipe_organisasi' => 'Tipe Organisasi',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function getConvertionNkk($params) {

        $value = $params;

        //4 ≤ X ≤ 5 => 105% s/d ≤ 110%
        if ($value == 5) {
            $konversi = 1.10;
        }
        else if ($value >= 4 && $value < 5 ) {
            $konversi = 1.05 ;
        }
        //3 ≤ X < 4 => 100% s/d <105%
        else if ($value >= 3 && $value < 4 ) {
            $konversi = 1.00;
        }
        //2.4 ≤ X <3 => 80% s/d <100%
        else if ($value >= 2.4 && $value < 3) {
            $konversi = 0.8;
        }
        //< 2.4 => 0%
        else if ($value < 2.4) {
            $konversi = 0.00;
        }
        else {
            $konversi = "step exceeds the specified range";
        }

        return $konversi;
    }


    public function getConvertionNku($params) {
        $value = $params;

        //4≤ X ≤ 5 => 105% s/d ≤ 110%
        if ($value == 5) {
            $konversi = 1.10;
        }
        else if ($value >= 4 && $value <= 5) {
            $konversi = 1.05;
        }
        //3≤ X <4 => 100% s/d <105%
        else if ($value >= 3 && $value < 4) {
            $konversi = 1.00;
        }
        //1≤ X <3 => 80% s/d <100%
        else if ($value >= 1 && $value < 3) {
            $konversi = 0.8;
        }
        else {
            $konversi = "step exceeds the specified range";
        }

        return $konversi;
    }

    public function getConvertionNki($params) {
        $value = $params;

        //≥ 4.5 => 144%
        if ($value >= 4.5) {
            $konversi = 1.44;
        }
        //4≤ X <4.5 => 118% s/d <135%
        else if ($value >= 4 && $value < 4.5) {
            $konversi = 1.18;
        }
        //3≤ X <4 => 100% s/d <118%
        else if ($value >= 3 && $value < 4) {
            $konversi = 1.00;
        }
        //2≤ X <3 => 50% s/d <100%
        else if ($value >= 2 && $value < 3) {
            $konversi = 0.5;
        }
        //1≤ X <2 => 0%
        else if ($value >= 1 && $value < 2) {
            $konversi = 0;
        }
        else {
            $konversi = "step exceeds the specified range";
        }

        return $konversi;
    }
}
