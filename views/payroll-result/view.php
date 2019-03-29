<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model reward\models\PayrollResult */

$this->title = 'Bulan : ' . $model->period_bulan . ' - ' . $model->period_tahun;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-result-view">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'payroll_name',
            'period_bulan',
            'period_tahun',
            'element_name',
            [
                'attribute' => 'curr_amount',
                'format' => ['decimal', 2],
            ],
            'resource'
        ],
    ]) ?>

        <br/>
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
        </div>
    </div>
</div>
