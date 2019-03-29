<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model projection\models\BatchEntry */

$this->title = 'Batch Entry : '. $model->mstType->type . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Batch Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-entry-view">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mstType.type',
            'jumlah_orang',
            'bi',
            'bp',
            'created_at',
            'updated_at',
        ],
    ]) ?>
        </div>
    </div>
</div>
