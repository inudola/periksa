<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel projection\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <!-- <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?> -->
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'person_id',
            'nik',
            'nama',
            'title',
            'tanggal_masuk',
            //'employee_category',
            //'organization',
            //'job',
            //'band',
            //'location',
            //'kota',
            //'no_hp',
            //'email:email',
            //'gender',
            //'status_pernikahan',
            //'agama',
            //'tgl_lahir',
            //'kota_lahir:ntext',
            //'start_date_assignment',
            //'admins',
            //'nik_atasan',
            //'nama_atasan',
            //'medical_admin',
            //'section',
            //'department',
            //'division',
            //'bgroup',
            //'egroup',
            //'directorate',
            //'area',
            //'tgl_masuk',
            //'status',
            //'status_employee',
            //'start_date_status',
            //'end_date_status',
            //'bp',
            //'bi',
            //'edu_lvl',
            //'edu_faculty:ntext',
            //'edu_major:ntext',
            //'edu_institution',
            //'posisi',
            //'last_update_date',
            //'salary',
            //'tunjangan',
            //'tunjangan_jabatan',
            //'tunjangan_rekomposisi',
            //'structural',
            //'functional',
            //'no_ktp',
            //'suku',
            //'golongan_darah',
            //'no_npwp',
            //'alamat:ntext',
            //'nama_ibu',
            //'dpe',
            //'kode_kota',
            //'position_id',
            //'homebase',

            //['class' => 'yii\grid\ActionColumn'],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>
        </div>
    </div>
</div>
