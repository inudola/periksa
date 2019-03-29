<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reward\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">
    <div class="box">
        <div class="box-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="form-group">
                <?php if (empty($model->setup_name)) {
                    ?>
                    <?= $form->field($model, 'setup_name')->textInput(['maxlength' => true]) ?>
                <?php } else { ?>
                    <?= $form->field($model, 'setup_name')->hiddenInput(['maxlength' => true, 'disabled' => true])->label(false) ?>
                <?php } ?>

                <?= $form->field($model, 'value_max')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'group_nature')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(\reward\models\MstNature::find()->where(['status' => 1])->all(), 'id', 'nature_name'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>

                <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Not Active',]) ?>

            </div>


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
     
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>
