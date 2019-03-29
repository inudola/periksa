<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;
use common\models\AuthAssignmentSearch;

/* @var $this yii\web\View */
/* @var $model app\models\EskSection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'item_name')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(AuthItem::find()->where(['name' => (new AuthAssignmentSearch)->filterItem])->all(), 'name', 'name'),
                'options' => ['placeholder' => '.: Select a assignment :.'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'user_id')->textInput(['autofocus' => true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
