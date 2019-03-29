<?php

use kartik\grid\GridView;

//use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PurchaseOrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="purchase-order-item-index">

    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table table-striped',
        ],
        'options' => [
            'class' => 'table-responsive',
        ],
        'dataProvider' => $dataProvider1,
        'export' => false,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 7%;']
            ],
            [
                'attribute' => 'mstNature.nature_code',
                'contentOptions' => ['style' => 'width: 15%;']
            ],
            [
                'attribute' => 'mstNature.nature_name',
                'contentOptions' => ['style' => 'width: 30%;']
            ],
            [
                'attribute' => 'my_sum',
                'format' => ['decimal', 2],
                'contentOptions' => ['style' => 'width: 30%;']
            ],
//            [
//                'class' => '\kartik\grid\ActionColumn',
//                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
//            ]

        ],
        'responsive' => true,
        'hover' => true,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
        ],
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => '50'],
        //'showPageSummary' => true,
//        'panel' => [
//            'heading'=>'',
//            'type'=>'success',
//            'before'=>Html::a('Create Simulation', ['/simulation/create'], ['class' => 'btn btn-success']),
//            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['class' => 'btn btn-default', 'title'=>('Reset Grid')]),
//            'showFooter'=>false
//        ],
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
    ]); ?>

</div>