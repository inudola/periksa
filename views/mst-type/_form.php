<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model projection\models\MstType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mst-batch-form">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>


            <div class="form-group">
                <?php if (empty($model->type)) {
                    ?>
                    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>
                <?php } else { ?>
                    <?= $form->field($model, 'type')->hiddenInput(['maxlength' => true, 'disabled' => true])->label(false) ?>
                <?php } ?>

                <?= $form->field($model, 'isYear')->dropDownList(['Y' => 'Tahunan', 'N' => 'Bulanan'], ['prompt' => '']) ?>
            </div>


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
