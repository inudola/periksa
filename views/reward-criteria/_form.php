<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\components\Helpers;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model admin\models\RewardCriteria */
/* @var $form yii\widgets\ActiveForm */

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
                            'multiple' => true
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


            <div class="box-header">
                <h4>Reward Criteria Details</h4>
            </div>

            <div class="reward-criteria-index">
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 6%">No</th>
                        <th style="width: 40%">Criteria Name</th>
                    </tr>

                    <tr>
                        <?php
                        $no = 0;
                        foreach ($criteria

                        as $rows) {
                        $no++;
                        ?>
                        <td><?= $no ?></td>
                        <td><?= $rows->criteria_name ?></td>
                    </tr>

                    <?php }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
