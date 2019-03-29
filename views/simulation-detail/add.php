<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\models\MstType;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model projection\models\BatchEntry */

$this->title = 'Create Batch Entry';
$this->params['breadcrumbs'][] = ['label' => 'Batch Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-entry-create">

    <div class="box">
        <div class="box-header">
            <h3>Bulan Januari 2018</h3>
        </div>
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'simulation_id')->textInput(['value'=>$id])->label(false); ?>

            <div class="col-md-6">
                <?= $form->field($model, 'type_id')->widget(Select2::classname(), [

                    'data' => ArrayHelper::map(MstType::find()->all(), 'id', 'type'),
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select type ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'jumlah_orang')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'bi')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'bp')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
