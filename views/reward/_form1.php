<?php

use yii\helpers\Html;
use reward\components\Helpers;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use reward\models\Employee;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\MultipleInputColumn;
use kartik\widgets\SwitchInput;

use unclead\multipleinput\TabularInput;

/* @var $this yii\web\View */
/* @var $model app\models\Reward */
/* @var $form yii\widgets\ActiveForm */
$fieldExists = [];
foreach (\reward\components\Helpers::getCriteria() as $criterion => $desc) {
    $fieldExists[$criterion] = false;
}

?>

    <div class="reward-form">

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

                <?php
                foreach ($rewardCriteria as $rows) {
                    if (array_key_exists($rows->criteria_name, $fieldExists)) {
                        $fieldExists[$rows->criteria_name] = true;
                    }
                }
                ?>

                <div class="row">

                    <?= $form->field($model, 'mst_reward_id')->hiddenInput(['maxlength' => true, 'value' => $params])->label(false) ?>

                    <?php if ( $fieldExists['emp_category'] ) { ?>
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
                    <?php } ?>

                    <?php if ( $fieldExists['gender'] ) { ?>
                        <div class="col-md-4" style="margin-right: -15px">
                            <?= $form->field($model, 'gender')->dropDownList(['F' => 'Perempuan', 'M' => 'Laki - laki'], ['prompt' => '']) ?>
                        </div>
                    <?php } ?>

                    <?php if ( $fieldExists['marital_status'] ) { ?>
                        <div class="col-md-4" style="margin-right: -15px">
                            <?= $form->field($model, 'marital_status')->dropDownList(['M' => 'Menikah', 'S' => 'Single'], ['prompt' => '']) ?>
                        </div>
                    <?php } ?>

                </div>

                <div class="row">
                    <div class="col-md-12">

                        <?=
                        TabularInput::widget([
                            'models' => $models,
                            'attributeOptions' => [
                                'enableAjaxValidation' => false,
                                'enableClientValidation' => false,
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
                                    'options' => ['prompt' => '',
                                        'attributeOptions' => $fieldExists['band_individu']
                                    ],
                                    'headerOptions' => [
                                        'style' => 'width: 34%;',
                                        'class' => 'day-css-class'
                                    ],

                                ],
                                [
                                    'name' => 'band_position',
                                    'type' => MultipleInputColumn::TYPE_DROPDOWN,
                                    'enableError' => true,
                                    'title' => 'Band Position',
                                    'items' => Helpers::getBpList(),
                                    'options' => ['prompt' => '',
                                        'attributeOptions' => $fieldExists['band_position']],
                                    'headerOptions' => [
                                        'style' => 'width: 34%;',
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
                                        'onkeyup' => 'this.value=numberWithCommas(this.value)'
                                    ],
                                    'headerOptions' => [
                                        'style' => 'width: 42%; margin-left:15px;',
                                    ]
                                ],
                            ],
                        ]);

                        ?>

                    </div>
                </div>

                <div class="row">
                    <?php if ( $fieldExists['structural'] ) { ?>
                        <div class="col-md-4" style="margin-right: -15px">
                            <?= $form->field($model, 'structural')->dropDownList(['Y' => 'Ya'], ['prompt' => '']) ?>
                        </div>
                    <?php } ?>

                    <?php if ( $fieldExists['functional'] ) { ?>
                        <div class="col-md-4" style="margin-right: -15px">
                            <?= $form->field($model, 'functional')->dropDownList(['Y' => 'Ya'], ['prompt' => '']) ?>
                        </div>
                    <?php } ?>

                    <?php if ( $fieldExists['kota'] ) { ?>
                        <div class="col-md-4" style="margin-right: -15px">
                            <?= $form->field($model, 'kota')->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(\reward\models\Kota::find()->all(), 'kota', 'kota'),
                                'language' => 'en',
                                'options' => ['placeholder' => 'Select Kota ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                        </div>
                    <?php } ?>
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
