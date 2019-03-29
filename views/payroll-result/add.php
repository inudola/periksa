<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\PayrollResult */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Add Payroll Result';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="payroll-result-form">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'payroll_name')->hiddenInput(['maxlength' => true, 'value' => 'TELKOMSEL'])->label(false) ?>

            <?= $form->field($model, 'period_bulan')->hiddenInput(['value' => $bulan])->label(false) ?>

            <?= $form->field($model, 'period_tahun')->hiddenInput(['value' => $tahun])->label(false) ?>

            <?= $form->field($model, 'resource')->hiddenInput(['value' => $resource])->label(false) ?>

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


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
