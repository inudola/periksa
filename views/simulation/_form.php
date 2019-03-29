<?php

use kartik\alert\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use reward\components\Helpers;

?>

<div class="simulation-form">

    <!-- alert message for parent ID-->
    <?php
    echo Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'title' => 'Warning!',
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'body' => "Description field can't be empty, please enter a value first!",
        'showSeparator' => false,
        'delay' => false,
        'options' => ['id'=>'msg-parent-description', 'style' => 'display:none;'],
    ]);
    ?>
    <?php
    echo Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'title' => 'Warning!',
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'body' => "Some data doesn't have proposed start date, please enter start date first!",
        'showSeparator' => false,
        'delay' => false,
        'options' => ['id'=>'msg-parent-start-date', 'style' => 'display:none;'],
    ]);
    ?>
    <?php
    echo Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'title' => 'Warning!',
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'body' => "Some data doesn't have proposed end date, please enter end date first!",
        'showSeparator' => false,
        'delay' => false,
        'options' => ['id'=>'msg-parent-end-date', 'style' => 'display:none;'],
    ]);
    ?>


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
                    <?= $form->field($model, 'description')->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'type')->widget(Select2::classname(), [
                        'data' => Helpers::getType(),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select type ...', 'id' => 'general_inc-id'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
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
                        <?= Html::submitButton('Save', ['id' => 'btnSubmit','class' => 'btn btn-success']) ?>
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
    
    $("#btnSubmit").click(function(){
            var start_date = $("#simulation-start_date").val();
            var end_date = $("#simulation-end_date").val();
            var description = $("#simulation-description").val();
            
        
            if(start_date.length <= 0){
                //show msg warning
                $("#msg-parent-start-date").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-start-date").fadeOut();
                }, 5000);
                return false;
            } else if(end_date.length <= 0){
                //show msg warning
                $("#msg-parent-end-date").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-end-date").fadeOut();
                }, 5000);
                return false;
            } else if(description.length <= 0){
                //show msg warning
                $("#msg-parent-description").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-description").fadeOut();
                }, 5000);
                return false;
            } else{
                //click btnGenerate
                $('.se-pre-con').show();
                $("#form_generate").submit();
                //return true;
            }
        });
});

JS;
$this->registerJs($js);