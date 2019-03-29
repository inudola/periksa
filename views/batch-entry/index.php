<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel projection\models\BatchEntrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Batch Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-entry-index">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Batch Entry', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mstType.type',
            'jumlah_orang',
            'bi',
            'bp',
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2],
            ],
            'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

        </div>
    </div>
</div>
