<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\EskSection */
/* @var $form yii\widgets\ActiveForm */

// Get the initial employee relation
if(empty($model->nik) || empty($model->employee)){
    $empData = '';
}else{
    $empData = $model->employee->nama." (".$model->employee->title.")";
}
?>

<div class="access-lists-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model,'nik')->widget(Select2::classname(),[
                'initValueText' => $empData,
                'options' => ['placeholder' => 'Search for a employee ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['access-lists/emplist']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(emp) { return emp.text; }'),
                    'templateSelection' => new JsExpression('function (emp) { return emp.text; }'),
                ],])->label("Employee NIK (<span style='color:red;font-size:8pt;'>Not Required</span>)");
            ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'email') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
