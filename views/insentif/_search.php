<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\InsentifSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insentif-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nik') ?>

    <?= $form->field($model, 'bi') ?>

    <?= $form->field($model, 'band') ?>

    <?= $form->field($model, 'organisasi_nku') ?>

    <?php // echo $form->field($model, 'tipe_organisasi') ?>

    <?php // echo $form->field($model, 'smt') ?>

    <?php // echo $form->field($model, 'tahun') ?>

    <?php // echo $form->field($model, 'nkk') ?>

    <?php // echo $form->field($model, 'nku') ?>

    <?php // echo $form->field($model, 'nki') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
