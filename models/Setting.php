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

    //POIN
    const INDEX_ASUMSI_POINT = 'ASUMSI POINT';
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
    const INDEX_IS_TELKOM = 'INSENTIF SEMESTERAN TELKOM';
    const INDEX_IS_CONTRACT_PROF = 'CONTRACT PROF & EXPATRIATE';

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
            [['value_max', 'setup_name'], 'required'],
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

            if ($this->isNewRecord)
                $this->created_at = date("Y-m-d H:i:s");

            $this->updated_at = date("Y-m-d H:i:s");

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

        return $value;
    }
}
