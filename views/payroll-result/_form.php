<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\PayrollResult */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payroll-result-form">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'payroll_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'period_bulan')->textInput() ?>

            <?= $form->field($model, 'period_tahun')->textInput() ?>

            <?= $form->field($model, 'element_name')->textInput() ?>

            <?=
            $form->field($model, 'curr_amount')->widget(\yii\widgets\MaskedInput::className(), [

                'clientOptions' => [
                    'alias' => 'numeric',
                    'allowMinus' => false,
                    'groupSize' => 3,
                    'radixPoint' => ".",
                    'groupSeparator' => '.',
                    'autoGroup' => true,
                    'removeMaskOnSubmit' => true,
                    'required' => true
                ],


            ]);
            ?>

            <?= $form->field($model, 'resource')->dropDownList(['Revex' => 'Revex', 'Payroll' => 'Payroll'], ['prompt' => '']) ?>


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
