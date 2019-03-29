<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Reward */

$this->title = 'Update Reward: ' . $model->mstReward->reward_name;
$this->params['breadcrumbs'][] = ['label' => 'Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mstReward->reward_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reward-update">

    <?= $this->render('_formupdate', [
        'model' => $model,
        //'rewardCriteria' => $rewardCriteria
    ]) ?>

</div>
