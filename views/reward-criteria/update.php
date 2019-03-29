<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\models\RewardCriteria */

$this->title = 'Update Reward Criteria: ' . $model->mstReward->reward_name;
$this->params['breadcrumbs'][] = ['label' => 'Reward Criterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mstReward->reward_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reward-criteria-update">

    <?= $this->render('_formupdate', [
        'model' => $model,
    ]) ?>

</div>
