<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model projection\models\BatchEntry */

$this->title = 'Update Batch Entry: ' . $model->mstType->type ;
$this->params['breadcrumbs'][] = ['label' => 'Batch Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mstType->type, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="batch-entry-update">

    <?= $this->render('form-update', [
        'model' => $model,
    ]) ?>

</div>
