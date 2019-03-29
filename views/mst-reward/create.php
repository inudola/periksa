<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\MstReward */

$this->title = 'Create Mst Reward';
$this->params['breadcrumbs'][] = ['label' => 'Mst Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-reward-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
