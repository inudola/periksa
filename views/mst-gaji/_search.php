<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\MstGajiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mst-gaji-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bi') ?>

    <?= $form->field($model, 'gaji_dasar') ?>

    <?= $form->field($model, 'tunjangan_biaya_hidup') ?>

    <?= $form->field($model, 'tunjangan_jabatan_struktural') ?>

    <?php // echo $form->field($model, 'tunjangan_jabatan_functional') ?>

    <?php // echo $form->field($model, 'tunjangan_rekomposisi') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
