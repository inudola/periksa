<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\MstElement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mst-element-form">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'element_name')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'type')->dropDownList(['PAYROLL' => 'PAYROLL', 'NON PAYROLL' => 'NON PAYROLL',], ['prompt' => '']) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Not Active',]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
