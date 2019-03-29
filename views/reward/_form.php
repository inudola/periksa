<?php

use kartik\alert\Alert;
use kartik\select2\Select2;
use reward\components\Helpers;
use unclead\multipleinput\MultipleInputColumn;
use unclead\multipleinput\TabularInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\models\MstCity;

/* @var $this yii\web\View */
/* @var $model app\models\Reward */
/* @var $form yii\widgets\ActiveForm */

?>

    <div class="reward-form">

        <!-- alert message for parent ID-->
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Amount field can't be empty, please enter a value first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-value', 'style' => 'display:none;'],
        ]);
        ?>

        <div class="box">
            <div class="box-body">

                <?php $form = ActiveForm::begin([
                    'id' => 'tabular-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => true,
                    'validateOnChange' => false,
                    'validateOnSubmit' => true,
                    'validateOnBlur' => false,
                ]); ?>

                <div class="row">

                    <?= $form->field($model, 'mst_reward_id')->hiddenInput(['maxlength' => true, 'value' => $params])->label(false) ?>

                    <div class="col-md-4" style="margin-right: -15px">
                        <?= $form->field($model, 'emp_category')->widget(Select2::classname(), [
                            'data' => Helpers::getEmpCategoryList(),
                            'language' => 'en',
                            'options' => ['prompt' => ''],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-md-4" style="margin-right: -15px">
                        <?= $form->field($model, 'gender')->dropDownList(['F' => 'Perempuan', 'M' => 'Laki - laki'], ['prompt' => '']) ?>
                    </div>

                    <div class="col-md-4" style="margin-right: -15px">
                        <?= $form->field($model, 'marital_status')->dropDownList(['M' => 'Menikah', 'S' => 'Single'], ['prompt' => '']) ?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-4" style="margin-right: -15px">
                        <?= $form->field($model, 'structural')->dropDownList(['Y' => 'Ya'], ['prompt' => '']) ?>
                    </div>

                    <div class="col-md-4" style="margin-right: -15px">
                        <?= $form->field($model, 'functional')->dropDownList(['Y' => 'Ya'], ['prompt' => '']) ?>
                    </div>

                    <div class="col-md-4" style="margin-right: -15px">
                        <?= $form->field($model, 'kota')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(MstCity::find()->all(), 'name', 'name'),
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select Kota ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">

                        <?=
                        TabularInput::widget([
                            'models' => $models,
                            'attributeOptions' => [
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => true,
                                'validateOnChange' => false,
                                'validateOnSubmit' => true,
                                'validateOnBlur' => false,
                            ],
                            'id' => 'reward',
                            'allowEmptyList' => false,
                            'cloneButton' => true,
                            'columns' => [
                                [
                                    'name' => 'band_individu',
                                    'type' => MultipleInputColumn::TYPE_DROPDOWN,
                                    'enableError' => true,
                                    'title' => 'Band Individu',
                                    'items' => Helpers::getBiList(),
                                    'options' => ['prompt' => ''],
                                    'headerOptions' => [
                                        'style' => 'width: 22%;',
                                        'class' => 'day-css-class'
                                    ],

                                ],
                                [
                                    'name' => 'band_position',
                                    'type' => MultipleInputColumn::TYPE_DROPDOWN,
                                    'enableError' => true,
                                    'title' => 'Band Position',
                                    'items' => Helpers::getBpList(),
                                    'options' => ['prompt' => ''],
                                    'headerOptions' => [
                                        'style' => 'width: 22%;',
                                        'class' => 'day-css-class'
                                    ]
                                ],
                                [
                                    'name' => 'band',
                                    'type' => MultipleInputColumn::TYPE_DROPDOWN,
                                    'enableError' => true,
                                    'title' => 'Band',
                                    'items' => Helpers::getBandList(),
                                    'options' => ['prompt' => ''],
                                    'headerOptions' => [
                                        'style' => 'width: 22%;',
                                        'class' => 'day-css-class'
                                    ]
                                ],
                                [
                                    'name' => 'amount',
                                    'title' => 'Amount',
                                    'enableError' => true,
                                    'options' => [
                                        //'id' => 'amount-item',
                                        'class' => 'input-priority',
                                        'style' => 'margin-left:5px',
                                        'value' => '50',
                                        'size' => '25',
                                        'onkeypress' => 'return forceNumber(event)',
                                        'onkeyup' => 'this.value=numberWithCommas(this.value)',
                                        'allowEmptyList' => false,
                                    ],
                                    'headerOptions' => [
                                        'style' => 'width: 22%; margin-left:15px;',
                                    ]
                                ],
                            ],
                        ]);

                        ?>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?= Html::submitButton('Save', ['id' => 'btnSubmit', 'class' => 'btn btn-success']) ?>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

    <script>
        function forceNumber(e) {
            var keyCode = e.keyCode ? e.keyCode : e.which;
            if ((keyCode < 48 || keyCode > 58) && keyCode != 188) {
                return false;
            }
            return true;
        }

        function numberWithCommas(n) {
            n = n.replace(/,/g, "");
            var s = n.split('.')[1];
            (s) ? s = "." + s : s = "";
            n = n.split('.')[0];
            while (n.length > 3) {
                s = "," + n.substr(n.length - 3, 3) + s;
                n = n.substr(0, n.length - 3)
            }
            return n + s;
        }

    </script>

<?php
$js = <<< JS
$(document).ready(function () {
    $("#btnSubmit").click(function(){
            var amount = $("#reward-0-amount").val();
            
            if(amount.length <= 0){
                //show msg warning
                $("#msg-parent-value").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-value").fadeOut();
                }, 5000);
                return false;
            } else{
                //click btnGenerate
                $('.se-pre-con').show();
                $("#tabular-form").submit();
                //return true;
            }
        });
});

        $('#reward').on('afterInit', function(){
            console.log('calls on after initialization event');
        }).on('beforeAddRow', function(e) {
            console.log('calls on before add row event');
            return confirm('Are you sure you want to add row?')
        }).on('afterAddRow', function(e, row) {
            console.log('calls on after add row event', $(row));
        }).on('beforeDeleteRow', function(e, item){
            console.log(item);
            console.log('calls on before remove row event');
            return confirm('Are you sure you want to delete row?')
        }).on('afterDeleteRow', function(e, item){       
            console.log('calls on after remove row event');
            console.log('Band_individu:' + item.find('.list-cell__band_individu').find('select').first().val());
        }).on('afterDropRow', function(e, item){       
            console.log('calls on after drop row', item);
        });


JS;
$this->registerJs($js);
