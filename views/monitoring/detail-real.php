<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PurchaseOrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->params['breadcrumbs'][] = $this->title;

$detailTemplate = '{view}, {update}, {delete}';

?>
<div class="purchase-order-item-index">

    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table table-striped',
        ],
        'options' => [
            'class' => 'table-responsive',
        ],
        'dataProvider' => $dataProvider,
        'export' => false,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 5%;']
            ],

//            [
//                'attribute' => 'bulan',
//                'value' => function ($model) {
//                    return $model->GetMonth();
//                }
//            ],
//            'tahun',
            [
                'attribute' => 'element_name',
                'contentOptions' => ['style' => 'width: 40%;']
            ],
            [
                'attribute' => 'curr_amount',
                'format' => ['decimal', 2],
                //'pageSummary' => true,
                'contentOptions' => ['style' => 'width: 35%;']

            ],


//            [
//                'class' => 'kartik\grid\ActionColumn',
//                'header' => 'Action',
//                //'contentOptions' => ['style' => 'width: 25%;'],
//                'buttons' => [
//
//                    'view' => function ($url, $model1, $key) {
//                        $urlConfig = [];
//
//                        foreach ($model1->primaryKey() as $pk) {
//                            $urlConfig['id'] = $model1->$pk;
//                        }
//
//                        $url = Url::toRoute(array_merge(['/payroll-result/view'], $urlConfig));
//                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
//                            $url, [
//                                'title' => 'View',
//                                'data-pjax' => '0',
//                                'class' => 'btn btn-sm btn-primary',
//                            ]);
//                    },
//                    'update' => function ($url, $model, $key) {
//                        $urlConfig = [];
//                        //foreach ($model->primaryKey() as $pk) {
//                        $urlConfig['id'] = $model->id;
//                        //}
//
//                        $url = Url::toRoute(array_merge(['/payroll-result/update'], $urlConfig));
//                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
//                            $url, [
//                                'title' => 'Edit',
//                                'data-pjax' => '0',
//                                'class' => 'btn btn-sm btn-success',
//                            ]);
//                    },
//                    'delete' => function ($url, $model, $key) {
//                        $urlConfig = [];
//                        foreach ($model->primaryKey() as $pk) {
//                            $urlConfig['id'] = $model->$pk;
//                        }
//
//                        $url = Url::toRoute(array_merge(['/payroll-result/delete'], $urlConfig));
//                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
//                            $url, [
//                                'title' => 'Delete',
//                                'data-pjax' => '0',
//                                'class' => 'btn btn-sm btn-danger btn-delete',
//                                'data' => [
//                                    'confirm' => 'Are you sure you want to delete this item?',
//                                    'method' => 'post',
//                                ],
//                            ]);
//                    }
//
//                ],
//                'template' => $detailTemplate
//            ],

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