<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use reward\models\Reward;
use kartik\select2\Select2;
use reward\components\Helpers;

/* @var $this yii\web\View */
/* @var $model admin\models\RewardCriteria */
/* @var $form yii\widgets\ActiveForm */
$params =Yii::$app->request->get('mst_reward_id');
?>

<div class="reward-criteria-form">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <div class="row">
                <?php $form = ActiveForm::begin(); ?>

                    <div class="col-md-6">
                    <?= $form->field($model, 'criteria_name')->widget(Select2::classname(), [
                        'data' => Helpers::getCriteria(),
                        'language' => 'en',
                        'options' => ['placeholder' => 'Select Criteria ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'multiple' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'mst_reward_id')->hiddenInput(['maxlength' => true, 'value' => $params])->label(false); ?>
                </div>



            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
