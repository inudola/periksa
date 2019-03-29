<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model projection\models\EmployeeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'person_id') ?>

    <?= $form->field($model, 'nik') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'tanggal_masuk') ?>

    <?php // echo $form->field($model, 'employee_category') ?>

    <?php // echo $form->field($model, 'organization') ?>

    <?php // echo $form->field($model, 'job') ?>

    <?php // echo $form->field($model, 'band') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'kota') ?>

    <?php // echo $form->field($model, 'no_hp') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'status_pernikahan') ?>

    <?php // echo $form->field($model, 'agama') ?>

    <?php // echo $form->field($model, 'tgl_lahir') ?>

    <?php // echo $form->field($model, 'kota_lahir') ?>

    <?php // echo $form->field($model, 'start_date_assignment') ?>

    <?php // echo $form->field($model, 'admins') ?>

    <?php // echo $form->field($model, 'nik_atasan') ?>

    <?php // echo $form->field($model, 'nama_atasan') ?>

    <?php // echo $form->field($model, 'medical_admin') ?>

    <?php // echo $form->field($model, 'section') ?>

    <?php // echo $form->field($model, 'department') ?>

    <?php // echo $form->field($model, 'division') ?>

    <?php // echo $form->field($model, 'bgroup') ?>

    <?php // echo $form->field($model, 'egroup') ?>

    <?php // echo $form->field($model, 'directorate') ?>

    <?php // echo $form->field($model, 'area') ?>

    <?php // echo $form->field($model, 'tgl_masuk') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'status_employee') ?>

    <?php // echo $form->field($model, 'start_date_status') ?>

    <?php // echo $form->field($model, 'end_date_status') ?>

    <?php // echo $form->field($model, 'bp') ?>

    <?php // echo $form->field($model, 'bi') ?>

    <?php // echo $form->field($model, 'edu_lvl') ?>

    <?php // echo $form->field($model, 'edu_faculty') ?>

    <?php // echo $form->field($model, 'edu_major') ?>

    <?php // echo $form->field($model, 'edu_institution') ?>

    <?php // echo $form->field($model, 'posisi') ?>

    <?php // echo $form->field($model, 'last_update_date') ?>

    <?php // echo $form->field($model, 'salary') ?>

    <?php // echo $form->field($model, 'tunjangan') ?>

    <?php // echo $form->field($model, 'tunjangan_jabatan') ?>

    <?php // echo $form->field($model, 'tunjangan_rekomposisi') ?>

    <?php // echo $form->field($model, 'structural') ?>

    <?php // echo $form->field($model, 'functional') ?>

    <?php // echo $form->field($model, 'no_ktp') ?>

    <?php // echo $form->field($model, 'suku') ?>

    <?php // echo $form->field($model, 'golongan_darah') ?>

    <?php // echo $form->field($model, 'no_npwp') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'nama_ibu') ?>

    <?php // echo $form->field($model, 'dpe') ?>

    <?php // echo $form->field($model, 'kode_kota') ?>

    <?php // echo $form->field($model, 'position_id') ?>

    <?php // echo $form->field($model, 'homebase') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
