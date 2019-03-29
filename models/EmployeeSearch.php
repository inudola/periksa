<?php

namespace reward\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EmployeeSearch represents the model behind the search form of `projection\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['person_id', 'nik', 'nama', 'title', 'tanggal_masuk', 'employee_category', 'organization', 'job', 'band', 'location', 'kota', 'no_hp', 'email', 'gender', 'status_pernikahan', 'agama', 'tgl_lahir', 'kota_lahir', 'start_date_assignment', 'admins', 'nik_atasan', 'nama_atasan', 'medical_admin', 'section', 'department', 'division', 'bgroup', 'egroup', 'directorate', 'area', 'tgl_masuk', 'status', 'status_employee', 'start_date_status', 'end_date_status', 'bp', 'bi', 'edu_lvl', 'edu_faculty', 'edu_major', 'edu_institution', 'posisi', 'last_update_date', 'structural', 'functional', 'no_ktp', 'suku', 'golongan_darah', 'no_npwp', 'alamat', 'nama_ibu', 'dpe', 'kode_kota', 'homebase'], 'safe'],
            [['salary', 'tunjangan', 'tunjangan_jabatan', 'tunjangan_rekomposisi', 'position_id'], 'integer'],
            [['score'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Employee::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tanggal_masuk' => $this->tanggal_masuk,
            'tgl_lahir' => $this->tgl_lahir,
            'start_date_assignment' => $this->start_date_assignment,
            'tgl_masuk' => $this->tgl_masuk,
            'start_date_status' => $this->start_date_status,
            'end_date_status' => $this->end_date_status,
            'last_update_date' => $this->last_update_date,
            'salary' => $this->salary,
            'tunjangan' => $this->tunjangan,
            'tunjangan_jabatan' => $this->tunjangan_jabatan,
            'tunjangan_rekomposisi' => $this->tunjangan_rekomposisi,
            'dpe' => $this->dpe,
            'position_id' => $this->position_id,
        ]);

        $query->andFilterWhere(['like', 'person_id', $this->person_id])
            ->andFilterWhere(['like', 'nik', $this->nik])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'employee_category', $this->employee_category])
            ->andFilterWhere(['like', 'organization', $this->organization])
            ->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'band', $this->band])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'kota', $this->kota])
            ->andFilterWhere(['like', 'no_hp', $this->no_hp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'status_pernikahan', $this->status_pernikahan])
            ->andFilterWhere(['like', 'agama', $this->agama])
            ->andFilterWhere(['like', 'kota_lahir', $this->kota_lahir])
            ->andFilterWhere(['like', 'admins', $this->admins])
            ->andFilterWhere(['like', 'nik_atasan', $this->nik_atasan])
            ->andFilterWhere(['like', 'nama_atasan', $this->nama_atasan])
            ->andFilterWhere(['like', 'medical_admin', $this->medical_admin])
            ->andFilterWhere(['like', 'section', $this->section])
            ->andFilterWhere(['like', 'department', $this->department])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'bgroup', $this->bgroup])
            ->andFilterWhere(['like', 'egroup', $this->egroup])
            ->andFilterWhere(['like', 'directorate', $this->directorate])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'status_employee', $this->status_employee])
            ->andFilterWhere(['like', 'bp', $this->bp])
            ->andFilterWhere(['like', 'bi', $this->bi])
            ->andFilterWhere(['like', 'edu_lvl', $this->edu_lvl])
            ->andFilterWhere(['like', 'edu_faculty', $this->edu_faculty])
            ->andFilterWhere(['like', 'edu_major', $this->edu_major])
            ->andFilterWhere(['like', 'edu_institution', $this->edu_institution])
            ->andFilterWhere(['like', 'posisi', $this->posisi])
            ->andFilterWhere(['like', 'structural', $this->structural])
            ->andFilterWhere(['like', 'functional', $this->functional])
            ->andFilterWhere(['like', 'no_ktp', $this->no_ktp])
            ->andFilterWhere(['like', 'suku', $this->suku])
            ->andFilterWhere(['like', 'golongan_darah', $this->golongan_darah])
            ->andFilterWhere(['like', 'no_npwp', $this->no_npwp])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'nama_ibu', $this->nama_ibu])
            ->andFilterWhere(['like', 'kode_kota', $this->kode_kota])
            ->andFilterWhere(['like', 'homebase', $this->homebase]);

        return $dataProvider;
    }
}
