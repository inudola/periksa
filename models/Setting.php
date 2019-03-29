<?php

namespace reward\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property int $id
 * @property string $setup_name
 * @property int $value_max
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const ACTIVE = 1;
    const INACTIVE = 0;

    //EMPLOYEE BPJS
    const IURAN_JKK = 'IURAN JKK';
    const IURAN_JKM = 'IURAN JKM';
    const IURAN_JHT = 'IURAN JHT';
    const IURAN_JP  = 'IURAN JP';
    const IURAN_KES = 'IURAN KES';
    const INDEX_JKES_MAX = 'JKES MAX UPAH';
    const INDEX_JP_MAX = 'JP MAX UPAH';

    const INDEX_THR_1 = 'THR 1';
    const INDEX_THR_2 = 'THR 2';

    const INDEX_TUNJANGAN_CUTI = 'TUNJANGAN CUTI';
    const INDEX_TUNJANGAN_AKHIR_TAHUN = 'TUNJANGAN AKHIR TAHUN';
    const INDEX_UANG_SAKU_AKHIR_PROGRAM = 'UANG SAKU AKHIR PROGRAM';

    const INDEX_CUTI_BESAR = 'CUTI BESAR';
    const INDEX_TAX = 'TAX';

    //POIN NKI
    const INDEX_ASUMSI_POINT_1 = 'ASUMSI POINT 1';
    const INDEX_ASUMSI_POINT_2 = 'ASUMSI POINT 2';
    const INDEX_TOTAL_POINT = 'TOTAL POINT';

    //pmk
    const INDEX_PMK_5 = 'MASA KERJA 5 TAHUN';
    const INDEX_PMK_10 = 'MASA KERJA 10 TAHUN';
    const INDEX_PMK_15 = 'MASA KERJA 15 TAHUN';
    const INDEX_PMK_20 = 'MASA KERJA 20 TAHUN';
    const INDEX_PMK_25 = 'MASA KERJA 25 TAHUN';
    const INDEX_PMK_30 = 'MASA KERJA 30 TAHUN';

    //IE
    const INDEX_IE_1 = 'IE BAND 1';
    const INDEX_IE_2 = 'IE BAND 2';
    const INDEX_IE_3 = 'IE BAND 3';
    const INDEX_IE_4 = 'IE BAND 4';
    const INDEX_IE_5 = 'IE BAND 5';
    const INDEX_IE_6 = 'IE BAND 6';
    const INDEX_IE_CONTRACT = 'IE CONTRACT';
    const INDEX_IE_TELKOM = 'IE TELKOM';

    //INSENTIF SEMESTERAN
    const INDEX_IS_TELKOM = 'IS TELKOM';
    const INDEX_IS_CONTRACT_PROF = 'IS CONTRACT PROF & EXPATRIATE';
    const INDEX_NKU = 'NKU';
    const INDEX_NKI = 'NKI';
    const INDEX_NKK = 'NKK';

    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value_max', 'setup_name', 'group_nature'], 'required'],
            [['status', 'description'], 'string'],
            [['created_at', 'updated_at', 'value_max'], 'safe'],
            [['value_max'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            [['setup_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'setup_name' => 'Setup Name',
            'value_max' => 'Value',
            'status' => 'Status',
            'group_nature' => 'Group Nature',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }



    public
    function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            //get format percentage increase
            $this->value_max = str_replace(",", ".", $this->value_max);
            $this->setup_name = strtoupper($this->setup_name);

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


    public function getBaseSetting($paramsName) {
        $settingTable = Setting::find()->where(['status' => Setting::ACTIVE])->asArray()->all();

        $key = array_search($paramsName, array_column($settingTable, 'setup_name'));

        $theSetting = $settingTable[$key];
        $value = $theSetting['value_max'];

        return floatval($value);
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

        return floatval($konversi);
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

        return floatval($konversi);
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

        return floatval($konversi);
    }


    public function getMstNature()
    {
        return $this->hasOne(MstNature::className(), ['id' => 'group_nature']);
    }
}
