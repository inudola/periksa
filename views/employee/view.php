<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model projection\models\Employee */

$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

    <p>
        <!-- <?= Html::a('Update', ['update', 'id' => $model->person_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->person_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?> -->
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'person_id',
            'nik',
            'nama',
            'title',
            'tanggal_masuk',
            'employee_category',
            'organization',
            'job',
            'band',
            'location',
            'kota',
            'no_hp',
            'email:email',
            'gender',
            'status_pernikahan',
            'agama',
            'tgl_lahir',
            'kota_lahir:ntext',
            'start_date_assignment',
            'admins',
            'nik_atasan',
            'nama_atasan',
            'medical_admin',
            'section',
            'department',
            'division',
            'bgroup',
            'egroup',
            'directorate',
            'area',
            'tgl_masuk',
            'status',
            'status_employee',
            'start_date_status',
            'end_date_status',
            'bp',
            'bi',
            'edu_lvl',
            'edu_faculty:ntext',
            'edu_major:ntext',
            'edu_institution',
            'posisi',
            'last_update_date',
            'salary',
            'tunjangan',
            'tunjangan_jabatan',
            'tunjangan_rekomposisi',
            'structural',
            'functional',
            'no_ktp',
            'suku',
            'golongan_darah',
            'no_npwp',
            'alamat:ntext',
            'nama_ibu',
            'dpe',
            'kode_kota',
            'position_id',
            'homebase',
        ],
    ]) ?>
        </div>
    </div>
</div>
