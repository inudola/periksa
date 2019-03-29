<?php

use reward\models\MstCity;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\components\Helpers;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;



/* @var $this yii\web\View */
/* @var $model app\models\Reward */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="reward-update-form">

    <div class="box">
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <?= $form->field($model, 'mst_reward_id')->hiddenInput(['maxlength' => true])->label(false) ?>

                <div class="col-md-4">
                    <?= $form->field($model, 'emp_category')->widget(Select2::classname(), [
                        'data' => Helpers::getEmpCategoryList(),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Employee Category ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'structural')->dropDownList(['Y' => 'Ya'], ['prompt' => '']) ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'functional')->dropDownList(['Y' => 'Ya'], ['prompt' => '']) ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'band_position')->widget(Select2::classname(), [
                        'data' => Helpers::getBpList(),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select band position ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'band_individu')->widget(Select2::classname(), [
                        'data' => Helpers::getBiList(),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select band individu ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($model, 'band')->widget(Select2::classname(), [
                        'data' => Helpers::getBandList(),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select band ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
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
                            'maxlength' => true
                        ]

                    ]);
                    ?>
                </div>


                <div class="col-md-4">
                    <?= $form->field($model, 'gender')->dropDownList(['F' => 'Perempuan', 'M' => 'Laki - laki'], ['prompt' => '']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'marital_status')->dropDownList(['M' => 'Menikah', 'S' => 'Single'], ['prompt' => '']) ?>
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
                <div class="col-md-4">
                    <?= $form->field($model, 'isApproved')->dropDownList([-1 => 'Pending', 1 => 'Approved', 0 => 'Rejected'], ['prompt' => '']) ?>
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
