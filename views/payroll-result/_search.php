<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\PayrollResultSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payroll-result-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'payroll_name') ?>

    <?= $form->field($model, 'period_bulan') ?>

    <?= $form->field($model, 'period_tahun') ?>

    <?= $form->field($model, 'element_name') ?>

    <?php // echo $form->field($model, 'curr_amount') ?>


    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
