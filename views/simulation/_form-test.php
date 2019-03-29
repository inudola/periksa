<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use reward\components\Helpers;

?>

<div class="simulation-form">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'start_date')->widget(DatePicker::className(), [
                            'options' => ['placeholder' => 'Select start date'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]
                    ) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'end_date')->widget(DatePicker::className(), [
                            'options' => ['placeholder' => 'Select end date'],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true
                            ]
                        ]
                    ) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'nik')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'perc_inc_gadas')->textInput() ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'perc_inc_tbh')->textInput() ?>
                </div>

                <div class="col-md-6">
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

