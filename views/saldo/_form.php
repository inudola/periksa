<?php

use reward\components\Helpers;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use reward\models\Employee;

$employeeUrl = Url::to(['employee/options']);

$initEmployeeText = '';
$employeeModel = Employee::findOne($model->nik);
if ($employeeModel) {
    $initEmployeeText = '[' . $employeeModel->nik . '] ' . $employeeModel->nama;
}

/* @var $this yii\web\View */
/* @var $model projection\models\SaldoNki */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="saldo-nki-form">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'nik')->widget(Select2::classname(), [
                        'initValueText' => $initEmployeeText, // set the initial display text
                        'options' => ['placeholder' => 'Search for an employee ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => $employeeUrl,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(employee) { return employee.text; }'),
                            'templateSelection' => new JsExpression('function (employee) { return employee.text; }'),
                        ],
                    ])->label('Employee'); ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'smt')->dropDownList(['1' => '1', '2' => '2'], ['prompt' => '']) ?>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'tahun')->widget(Select2::classname(), [
                        'data' => Helpers::getTahun(),
                        'language' => 'en',
                        'options' => ['prompt' => ''],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'score')->textInput() ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
