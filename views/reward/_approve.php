<?php

use reward\models\MstCity;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\components\Helpers;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


$this->title = 'Approve Reward: ' . $model->mstReward->reward_name;
$this->params['breadcrumbs'][] = ['label' => 'Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mstReward->reward_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Approve';

/* @var $this yii\web\View */
/* @var $model app\models\Reward */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="reward-approve-form">

    <div class="box">
        <div class="box-body">

            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
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
