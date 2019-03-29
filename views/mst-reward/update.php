<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\MstReward */

$this->title = 'Update Mst Reward: ' . $model->reward_name;
$this->params['breadcrumbs'][] = ['label' => 'Mst Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->reward_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mst-reward-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
