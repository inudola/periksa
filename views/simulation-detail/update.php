<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model projection\models\SimulationDetail */

$this->title = 'Update '. $model->element. ' On '.$model->GetMonth().' '. $model->tahun;
$this->params['breadcrumbs'][] = ['label' => 'Simulation Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="simulation-detail-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
