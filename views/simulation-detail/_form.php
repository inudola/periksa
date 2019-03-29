<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\models\BatchEntry;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model projection\models\SimulationDetail */
/* @var $form yii\widgets\ActiveForm */



$keteranganList = [
    'ORIGINAL BUDGET' => 'ORIGINAL BUDGET',
    'ALTERNATIF 1' => 'ALTERNATIF 1',
    'ALTERNATIF 2' => 'ALTERNATIF 2',
    'ALTERNATIF 3' => 'ALTERNATIF 3',
    'ALTERNATIF 4' => 'ALTERNATIF 4',
    'ALTERNATIF 5' => 'ALTERNATIF 5',
];
?>

<div class="simulation-detail-form">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'simulation_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'tahun')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'element')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'parent_id')->hiddenInput(['value' => $model->id])->label(false); ?>

            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'bulan')->dropDownList(
                        [
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December'
                        ],

                        ['prompt' => '']) ?>
                </div>

                <div class="col-md-3">
                    <?=
                    //$form->field($model, 'amount')->textInput();
                    $form->field($model, 'amount')->widget(\yii\widgets\MaskedInput::className(), [

                        'clientOptions' => [
                            'alias' => 'numeric',
                            'allowMinus' => false,
                            'groupSize' => 3,
                            'radixPoint' => ".",
                            'groupSeparator' => '.',
                            'autoGroup' => true,
                            'removeMaskOnSubmit' => true
                        ]

                    ]);
                    ?>

                </div>

                <div class="col-md-3">
                    <?= $form->field($model, 'keterangan')->dropDownList($keteranganList, ['prompt' => '', 'required' => true]); ?>
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
