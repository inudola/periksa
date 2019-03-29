<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\BatchDetail */

$this->title = 'Update Batch Detail: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Batch Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="batch-detail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
