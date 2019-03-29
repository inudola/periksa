<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\RewardLog */

$this->title = 'Update Reward Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Reward Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reward-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
