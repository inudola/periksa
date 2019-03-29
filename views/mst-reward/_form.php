<?php

use dosamigos\ckeditor\CKEditor;
use kartik\alert\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use reward\models\Category;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model reward\models\MstReward */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mst-reward-form">
    <?php
    echo Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'title' => 'Warning!',
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'body' => "Reward Name field can't be empty, please enter a reward name first!",
        'showSeparator' => false,
        'delay' => false,
        'options' => ['id' => 'msg-parent-reward_name', 'style' => 'display:none;'],
    ]);

    echo Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'title' => 'Warning!',
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'body' => "Category field can't be empty, please enter a category first!",
        'showSeparator' => false,
        'delay' => false,
        'options' => ['id' => 'msg-parent-category', 'style' => 'display:none;'],
    ]);

    echo Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'title' => 'Warning!',
        'icon' => 'glyphicon glyphicon-exclamation-sign',
        'body' => "Description field can't be empty, please enter a description first!",
        'showSeparator' => false,
        'delay' => false,
        'options' => ['id' => 'msg-parent-desc', 'style' => 'display:none;'],
    ]);

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
    <div class="box">
        <div class="box-body">

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'generate-form']) ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'reward_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'categoryId')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Category::findAll(['status' => Category::ACTIVE]), 'id', 'category_name'),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Category ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'icon')->fileInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'file')->fileInput() ?>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList([1 => 'Active', 0 => 'Not Active',]) ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->widget(CKEditor::className(), [
                'options' => ['rows' => 1],
                'preset' => 'advanced'
            ]) ?>

            <?= $form->field($model, 'formula')->widget(CKEditor::className(), [
                'options' => ['rows' => 1],
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
    </div>
</div>


<?php
$js = <<< JS
$(document).ready(function () {
    $("#btnSubmit").click(function(){
            var icon = $("#mstreward-icon").val();
            var file = $("#mstreward-file").val();
            var rewardname = $("#mstreward-reward_name").val();
            var cat = $("#mstreward-categoryid").val();
            var description = $("#mstreward-description").val();

            if(rewardname.length <= 0){
                //show msg warning
                $("#msg-parent-reward_name").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-reward_name").fadeOut();
                }, 5000);
                return false;
            } else if(cat.length <= 0){
                //show msg warning
                $("#msg-parent-category").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-category").fadeOut();
                }, 5000);
                return false;
            } else if(description.length <= 0){
                //show msg warning
                $("#msg-parent-desc").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-desc").fadeOut();
                }, 5000);
                return false;
            }
            else if(icon.length <= 0){
                //show msg warning
                $("#msg-parent-icon").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-icon").fadeOut();
                }, 5000);
                return false;
            }
            else{
                //click btnGenerate
                $('.se-pre-con').show();
                $("#generate-form").submit();
                //return true;
            }
        });
});

JS;
$this->registerJs($js);