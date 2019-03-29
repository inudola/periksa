<?php

use dosamigos\ckeditor\CKEditor;
use kartik\alert\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use reward\models\CategoryType;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php
    echo Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'title' => 'Warning!',
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'body' => "Icon field can't be empty, please enter an icon first!",
        'showSeparator' => false,
        'delay' => false,
        'options' => ['id' => 'msg-parent-icon', 'style' => 'display:none;'],
    ]);
    ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'generate-form']) ?>

    <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'category_name')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Not Active',]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'category_type_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(CategoryType::find()->all(), 'id', 'name'),
                'language' => 'en',
                'options' => ['placeholder' => 'Select  ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'note')->textarea(['rows' => '3']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'icon')->fileInput() ?>
        </div>
    </div>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 3],
        'preset' => 'advanced'
    ]) ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= Html::submitButton('Save', ['id' => 'btnSubmit', 'class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>


<?php
$js = <<< JS
$(document).ready(function () {
    $("#btnSubmit").click(function(){
            var icon = $("#category-icon").val();
            
            if(icon.length <= 0){
                //show msg warning
                $("#msg-parent-icon").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-icon").fadeOut();
                }, 5000);
                return false;
            } else{
                //click btnGenerate
                $('.se-pre-con').show();
                $("#generate-form").submit();
                //return true;
            }
        });
});

JS;
$this->registerJs($js);