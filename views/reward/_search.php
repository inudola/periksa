<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RewardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reward-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'reward_name') ?>

    <?= $form->field($model, 'emp_category') ?>

    <?= $form->field($model, 'band_individu') ?>

    <?= $form->field($model, 'band_position') ?>

    <?php // echo $form->field($model, 'structural') ?>

    <?php // echo $form->field($model, 'functional') ?>

    <?php // echo $form->field($model, 'marital_status') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'categoryId') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
