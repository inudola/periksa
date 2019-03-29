<?php

use kartik\alert\Alert;
use kartik\select2\Select2;
use kartik\widgets\DepDrop;
use unclead\multipleinput\MultipleInputColumn;
use unclead\multipleinput\TabularInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$simulationId = Yii::$app->getRequest()->getQueryParam('simId');

$catList = [
    'PAYROLL' => 'PAYROLL',
    'NON PAYROLL' => 'NON PAYROLL'
];

$filterList = [
    'PERCENTAGE' => 'USE PERCENTAGE',
    'AMOUNT' => 'USE AMOUNT'
];

?>

    <div class="batch-entry-form">

        <!-- alert message for parent ID-->
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Element field can't be empty, please enter element first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id'=>'msg-parent-element', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed group, please enter group first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id'=>'msg-parent-group', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed amount, please enter amount first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id'=>'msg-parent-amount', 'style' => 'display:none;'],
        ]);
        ?>
        <div class="box">

            <div class="box-body">

                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <?= $form->field($model1, 'simulation_id')->hiddenInput(['value' => $simulationId])->label(false); ?>

<!--                    <div class="col-md-4">
                        <?/*= $form->field($model, 'type_element')->dropDownList($catList, ['prompt' => '', 'id' => 'cat-id', 'required' => true]); */?>
                    </div>-->

<!--                    <div class="col-md-4">

                        <?/*= $form->field($model1, 'element')->widget(DepDrop::classname(), [
                            'options' => ['id' => 'subcat-id', 'required' => true],
                            'pluginOptions' => [
                                'depends' => ['cat-id'],
                                'placeholder' => 'Select ...',
                                'url' => Url::to(['/mst-element/lists'])
                            ]
                        ])->label('Element Name');
                        */?>
                    </div>-->

                    <div class="col-md-4">
                        <?= $form->field($model1, 'element')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(\reward\models\MstElement::find()->asArray()->all(), 'element_name', 'element_name'),
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model1, 'n_group')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(\reward\models\MstNature::find()->asArray()->orderBy('nature_code')->where(['status' => 1])->all(), 'id', 'nature_name'),
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label('Group');
                        ?>
                    </div>

                    <div class="col-md-4">
                        <?=
                        $form->field($model, 'amount')->widget(\yii\widgets\MaskedInput::className(), [

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
                    </div>
                </div>

                <hr/>
                <div class="row">
                    <div class="col-md-12">
                        <h4 style="color: red">Advance Filter </h4>

                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'type_filter')->dropDownList($filterList, ['prompt' => '', 'id' => 'filter-id']); ?>
                            </div>
                        </div>

                        <div class="row type_filter" style="display:none;">
                            <div class="col-md-4">
                                <?=
                                TabularInput::widget([
                                    'models' => $models,
                                    'attributeOptions' => [
                                        'enableAjaxValidation' => false,
                                        'enableClientValidation' => false,
                                        'validateOnChange' => false,
                                        'validateOnSubmit' => false,
                                        'validateOnBlur' => false,
                                    ],
                                    'id' => 'reward',
                                    'allowEmptyList' => false,
                                    'cloneButton' => true,

                                    'columns' => [
                                        [
                                            'name' => 'bulan',
                                            'type' => MultipleInputColumn::TYPE_DROPDOWN,
                                            'enableError' => true,
                                            'title' => 'Bulan',
                                            'items' => ArrayHelper::map(\reward\models\SimulationDetail::findAll(['simulation_id' => $simulationId]), 'bulan', 'bulan'),
                                            'options' => ['prompt' => ''],
                                            'headerOptions' => [
                                                'style' => 'width: 30%;',
                                                'class' => 'day-css-class'
                                            ],

                                        ],

                                        [
                                            'name' => 'percentage',
                                            'title' => 'Percentage',
                                            'enableError' => true,
                                            'options' => [
                                                'id' => 'filter-percentage',
                                                'class' => 'input-priority',
                                                'style' => 'margin-left:5px;',
                                                'value' => '50',
                                                'size' => '25',
                                                'onkeypress' => 'return forceNumber(event)',
                                                'onkeyup' => 'this.value=numberWithCommas(this.value)',
                                                'allowEmptyList' => false,
                                                'autocomplete' => 'off'
                                            ],
                                            'headerOptions' => [
                                                'style' => 'margin-left:15px;',
                                                'id' => 'title-percentage'
                                            ]
                                        ],

                                        [
                                            'name' => 'nilai',
                                            'title' => 'Amount',
                                            'enableError' => true,
                                            'options' => [
                                                'id' => 'filter-nilai',
                                                'class' => 'input-priority',
                                                'style' => 'margin-left:5px;',
                                                'value' => '50',
                                                'size' => '25',
                                                'onkeypress' => 'return forceNumber(event)',
                                                'onkeyup' => 'this.value=numberWithCommas(this.value)',
                                                'allowEmptyList' => false,
                                                'autocomplete' => 'off'
                                            ],
                                            'headerOptions' => [
                                                'style' => 'margin-left:15px;',
                                                'id' => 'title-amount'
                                            ]
                                        ],
                                    ],
                                ]);

                                ?>
                            </div>
                        </div>

                    </div>
                </div>
                <hr/>
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
        $('#reward').on('afterInit', function(){
            console.log('calls on after initialization event');
        }).on('beforeAddRow', function(e) {
            console.log('calls on before add row event');
            return confirm('Are you sure you want to add row?')
        }).on('afterAddRow', function(e, row) {
            console.log('calls on after add row event', $(row));
            let val = $('#filter-id option:selected').val();
        //console.log(val);
            
            if(val == 'PERCENTAGE') {
                 $('.type_filter').show();
            $('#filter-percentage').show();
            $('#title-percentage').show();
            $('#filter-nilai').hide();  
            $('#title-amount').hide();
            $('.list-cell__percentage').show(); 
            $('.list-cell__nilai').hide(); 
            }
            else if(val == 'AMOUNT') {
            $('.type_filter').show();
            $('#filter-nilai').show();  
            $('#title-amount').show();
            $('#filter-percentage').hide();
            $('#title-percentage').hide();
            $('.list-cell__percentage').hide(); 
            $('.list-cell__nilai').show(); 
            } 
            
            else {
            $('.type_filter').hide();
            }

        }).on('beforeDeleteRow', function(e, item){
            console.log(item);
            console.log('calls on before remove row event');
            return confirm('Are you sure you want to delete row?')
        }).on('afterDeleteRow', function(e, item){       
            console.log('calls on after remove row event');
            console.log('Bulan:' + item.find('.list-cell__bulan').find('select').first().val());
        }).on('afterDropRow', function(e, item){       
            console.log('calls on after drop row', item);
        });

$(document).ready(function () {
    $(document.body).on('change', '#filter-id', function () {
        let val = $('#filter-id option:selected').val();
        //console.log(val);
            
            if(val == 'PERCENTAGE') {
                 $('.type_filter').show();
            $('#filter-percentage').show();
            $('#title-percentage').show();
            $('#filter-nilai').hide();  
            $('#title-amount').hide();
            $('.list-cell__percentage').show(); 
            $('.list-cell__nilai').hide(); 
            }
            else if(val == 'AMOUNT') {
            $('.type_filter').show();
            $('#filter-nilai').show();  
            $('#title-amount').show();
            $('#filter-percentage').hide();
            $('#title-percentage').hide();
            $('.list-cell__percentage').hide(); 
            $('.list-cell__nilai').show(); 
            } 
            
            else {
            $('.type_filter').hide();
            }

    });
    
    $("#btnSubmit").click(function(){
            var group = $("#simulationdetail-n_group").val();
            var element = $("#simulationdetail-element").val();
            var amount = $("#batchentry-amount").val();
            
        
            if(element.length <= 0){
                //show msg warning
                $("#msg-parent-element").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-element").fadeOut();
                }, 5000);
                return false;
            } else if(group.length <= 0){
                //show msg warning
                $("#msg-parent-group").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-group").fadeOut();
                }, 5000);
                return false;
            } else if(amount.length <= 0){
                //show msg warning
                $("#msg-parent-amount").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-amount").fadeOut();
                }, 5000);
                return false;
            } else{
                //click btnGenerate
                $('.se-pre-con').show();
                //$("#form_generate").submit();
                //return true;
            }
        });
});

JS;
$this->registerJs($js);




