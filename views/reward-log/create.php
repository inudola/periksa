<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\RewardLog */

$this->title = 'Create Reward Log';
$this->params['breadcrumbs'][] = ['label' => 'Reward Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
