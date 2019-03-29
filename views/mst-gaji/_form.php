<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\MstGaji */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mst-gaji-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gaji_dasar')->textInput() ?>

    <?= $form->field($model, 'tunjangan_biaya_hidup')->textInput() ?>

    <?= $form->field($model, 'tunjangan_jabatan_struktural')->textInput() ?>

    <?= $form->field($model, 'tunjangan_jabatan_functional')->textInput() ?>

    <?= $form->field($model, 'tunjangan_rekomposisi')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
