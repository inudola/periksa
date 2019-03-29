<?php

use kartik\widgets\DepDrop;
use reward\models\Employee;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use reward\components\Helpers;

$catList = [
    'PAYROLL' => 'PAYROLL',
    'NON PAYROLL' => 'NON PAYROLL'
];

$employeeUrl = Url::to(['employee/options']);

$initEmployeeText = '';
$employeeModel = Employee::findOne($model->nik);
if ($employeeModel) {
    $initEmployeeText = '[' . $employeeModel->nik . '] ' . $employeeModel->nama;
}

?>

<div class="simulation-form">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'start_date')->widget(DatePicker::className(), [
                            'options' => ['placeholder' => 'Select start date'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]
                    ) ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'end_date')->widget(DatePicker::className(), [
                            'options' => ['placeholder' => 'Select end date'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]
                    ) ?>
                </div>
                <div class="col-md-4">
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
            </div>
            <div class="row">

                <div class="col-md-4">
                    <?= $form->field($model, 'type_element')->dropDownList($catList, ['prompt' => '', 'id' => 'cat-id', 'required' => true]); ?>
                </div>

                <div class="col-md-4">

                    <?= $form->field($model, 'element')->widget(DepDrop::classname(), [
                        'options' => ['id' => 'subcat-id', 'required' => true],
                        'pluginOptions' => [
                            'depends' => ['cat-id'],
                            'placeholder' => 'Select ...',
                            'url' => Url::to(['/mst-element/lists'])
                        ]
                    ])?>

                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'type')->widget(Select2::classname(), [
                        'data' => Helpers::getType(),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select type ...', 'id' => 'general_inc-id'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Tipe Kenaikan');
                    ?>
                </div>
            </div>

            <div class="row general_increase" style="display:none;">
                <div class="col-md-4">
                    <?= $form->field($model, 'perc_inc_gadas')->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'perc_inc_tbh')->textInput() ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'perc_inc_rekomposisi')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::submitButton('Simulate', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<?php
$js = <<< JS
$(document).ready(function () {
    $(document.body).on('change', '#general_inc-id', function () {
        let val = $('#general_inc-id').val();
        console.log(val);
            if(val == 'general_increase') {
            $('.general_increase').show();
            }
            else {
                $('.general_increase').hide();
            }
            
    });
});

JS;
$this->registerJs($js);