<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\components\Helpers;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model reward\models\ElementDetail */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="element-detail-form">

    <div class="box">
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'band_individu')->widget(Select2::classname(), [
                        'data' => Helpers::getBiList(),
                        'language' => 'en',
                        'options' => ['prompt' => ''],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-6">
                    <?=
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
            </div>

            <?= $form->field($model, 'mst_element_id')->hiddenInput(['value' => $params])->label(false); ?>

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
