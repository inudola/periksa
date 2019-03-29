<?php

use kartik\select2\Select2;
use reward\components\Helpers;
use reward\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$employeeUrl = Url::to(['employee/options']);

$initEmployeeText = '';
$employeeModel = Employee::findOne($model->nik);
if ($employeeModel) {
    $initEmployeeText = '[' . $employeeModel->nik . '] ' . $employeeModel->nama;
}

/* @var $this yii\web\View */
/* @var $model reward\models\Insentif */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insentif-form">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="form-group">
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

            <div class="form-group">
                <?= $form->field($model, 'bi')->widget(Select2::classname(), [
                    'data' => Helpers::getBiList(),
                    'language' => 'en',
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'band')->widget(Select2::classname(), [
                    'data' => Helpers::getBandList(),
                    'language' => 'en',
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'organisasi_nku')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'tipe_organisasi')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'smt')->dropDownList(['1' => '1', '2' => '2'], ['prompt' => '']) ?>
            </div>

            <div class="form-group">
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

            <div class="form-group">
                <?= $form->field($model, 'nkk')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'nku')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'nki')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>