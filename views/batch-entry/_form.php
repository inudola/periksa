<?php

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
                    <?= $form->field($model1, 'simulation_id')->hiddenInput(['value' => $simulationId])->label(false); ?>

                    <div class="col-md-4">
                        <?= $form->field($model, 'type_element')->dropDownList($catList, ['prompt' => '', 'id' => 'cat-id', 'required' => true]); ?>
                    </div>

                    <div class="col-md-4">

                        <?= $form->field($model, 'type_id')->widget(DepDrop::classname(), [
                            'options' => ['id' => 'subcat-id', 'required' => true],
                            'pluginOptions' => [
                                'depends' => ['cat-id'],
                                'placeholder' => 'Select ...',
                                'url' => Url::to(['/mst-element/lists'])
                            ]
                        ]);
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

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body">
                                <h4>Advance Filter </h4>

                                <div class="form-group">
                                    <div class="col-md-4" style="padding: 0">
                                        <?= $form->field($model, 'type_filter')->dropDownList($filterList, ['prompt' => '', 'id' => 'filter-id', 'required' => true]); ?>
                                    </div>
                                </div>

                                <div class="form-group type_filter" style="display:none;">
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
                                                'name' => 'bulan',
                                                'type' => MultipleInputColumn::TYPE_DROPDOWN,
                                                'enableError' => true,
                                                'title' => 'Bulan',
                                                'items' => ArrayHelper::map(\reward\models\SimulationDetail::findAll(['simulation_id' => $simulationId]), 'bulan', 'bulan'),
                                                'options' => ['prompt' => ''],
                                                'headerOptions' => [
                                                    //'style' => 'width: 30%;',
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
                </div>


                <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
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
});

JS;
$this->registerJs($js);




