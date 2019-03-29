<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\models\MstType;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use reward\components\Helpers;
use fedemotta\datatables\DataTables;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model projection\models\BatchEntry */

?>
    <div class="batch-entry-create">


        <div class="box">
            <div class="box-header">
                <h3>Update</h3>
            </div>
            <div class="box-body">

                <?php $form = ActiveForm::begin(); ?>

                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(MstType::findAll(['isYear' => MstType::MONTH]), 'id', 'type'),
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select type ...', 'id' => 'prohire-id'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>

                    <div class="col-md-3">
                        <?= $form->field($model, 'jumlah_orang')->textInput() ?>
                    </div>
                </div>

                <div class="row type_trainee">
                    <div class="col-md-3">
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

                    <div class="col-md-3">
                        <?= $form->field($model, 'bp')->widget(Select2::classname(), [
                            'data' => Helpers::getBpList(),
                            'language' => 'en',
                            'options' => ['prompt' => ''],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row type_prohire" style="display:none;">
                    <div class="col-md-3">
                        <?= $form->field($model, 'perc_inc_gadas')->textInput() ?>
                    </div>

                    <div class="col-md-3">
                        <?= $form->field($model, 'perc_inc_tbh')->textInput() ?>
                    </div>
                </div>

                <div class="row type_prohire" style="display:none;">
                    <div class="col-md-3">
                        <?= $form->field($model, 'perc_inc_rekomposisi')->textInput() ?>
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

<?php
$js = <<< JS
$(document).ready(function () {
    $(document.body).on('change', '#prohire-id', function () {
        let val = $('#prohire-id option:selected').text();
        //console.log(val);
            if(val !== 'PROHIRE' && val !== 'TRAINEE') {
            $('.type_prohire').hide();
            $('.type_trainee').show();
            }
            else if(val == 'TRAINEE') {
            $('.type_trainee').hide();
            }
            else {
            $('.type_prohire').show();
            $('.type_trainee').show();
            } 

    });
});

JS;
$this->registerJs($js);