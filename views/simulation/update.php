<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model projection\models\Simulation */

$this->title = 'Update Simulation: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Simulations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="simulation-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
