<?php

use yii\helpers\Url;
use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PurchaseOrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->params['breadcrumbs'][] = $this->title;

$detailTemplate = '{update}, {delete}';
?>
<div class="purchase-order-item-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider1,
        'export' => false,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 10%;']
            ],

            [
                'attribute' => 'element',
                'contentOptions' => ['style' => 'width: 45%;']
            ],
            [
                'attribute' => 'my_sum',
                'format' => ['decimal', 2],
                //'contentOptions' => ['style' => 'width: 30%;']

            ],
/*            [
                'class' => '\kartik\grid\ActionColumn',
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            ]*/
            /*[
                'class' => 'kartik\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 14%;'],
                'buttons' => [

                    'view' => function ($url, $model, $key) {
                        $urlConfig = [];

                        $urlConfig['simId'] = $model->simulation_id;
                        $urlConfig['bulan'] = $model->bulan;
                        $urlConfig['tahun'] = $model->tahun;

                        $url = Url::toRoute(array_merge(['/batch-entry/view-batch'], $urlConfig));
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            $url, [
                                'title' => 'View',
                                'data-pjax' => '0',
                                'class' => 'btn btn-sm btn-primary',
                            ]);
                    },
                    'update' => function ($url, $model, $key) {
                        $urlConfig = [];
                        //foreach ($model->primaryKey() as $pk) {
                        $urlConfig['id'] = $model->id;
                        //}

                        $url = Url::toRoute(array_merge(['/simulation-detail/update'], $urlConfig));
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                            $url, [
                                'title' => 'Edit',
                                'data-pjax' => '0',
                                'class' => 'btn btn-sm btn-success',
                            ]);
                    },
                    'delete' => function ($url, $model, $key) {
                        $urlConfig = [];
                        //foreach ($model as $pk) {
                        $urlConfig['id'] = $model->id;
//                        $urlConfig['bulan'] = $model->bulan;
//                        $urlConfig['tahun'] = $model->tahun;
//                        $urlConfig['batch'] = $model->batch_id;
                        //}

                        $url = Url::toRoute(array_merge(['/simulation-detail/delete'], $urlConfig));
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            $url, [
                                'title' => 'Delete',
                                'data-pjax' => '0',
                                'class' => 'btn btn-sm btn-danger btn-delete',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]);
                    }

                ],
                'template' => $detailTemplate
            ],*/

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