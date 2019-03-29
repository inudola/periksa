<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model projection\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'person_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nik')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_masuk')->textInput() ?>

    <?= $form->field($model, 'employee_category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organization')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'band')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_hp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_pernikahan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_lahir')->textInput() ?>

    <?= $form->field($model, 'kota_lahir')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'start_date_assignment')->textInput() ?>

    <?= $form->field($model, 'admins')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nik_atasan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_atasan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'medical_admin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'section')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'department')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'division')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bgroup')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'egroup')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'directorate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tgl_masuk')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_employee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_date_status')->textInput() ?>

    <?= $form->field($model, 'end_date_status')->textInput() ?>

    <?= $form->field($model, 'bp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'edu_lvl')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'edu_faculty')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'edu_major')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'edu_institution')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'posisi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_update_date')->textInput() ?>

    <?= $form->field($model, 'salary')->textInput() ?>

    <?= $form->field($model, 'tunjangan')->textInput() ?>

    <?= $form->field($model, 'tunjangan_jabatan')->textInput() ?>

    <?= $form->field($model, 'structural')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'functional')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_ktp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'suku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'golongan_darah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_npwp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nama_ibu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dpe')->textInput() ?>

    <?= $form->field($model, 'kode_kota')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position_id')->textInput() ?>

    <?= $form->field($model, 'homebase')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
