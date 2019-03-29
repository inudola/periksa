<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\BatchDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="batch-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'simulation_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bulan')->textInput() ?>

    <?= $form->field($model, 'tahun')->textInput() ?>

    <?= $form->field($model, 'element')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'batch_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
