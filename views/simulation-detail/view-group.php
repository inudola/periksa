<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

//use miloschuman\highcharts\Highcharts;

$this->title = 'Group'. $model->element;
//$this->params['breadcrumbs'][] = $this->title;

$detailTemplate = '{view}, {update}, {delete}';
?>


<div class="simulation-detail-view-group">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $getModel,
                //'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'contentOptions' => ['style' => 'width: 5%;']
                    ],
                    [
                        'attribute' => 'element',
                        'contentOptions' => ['style' => 'width: 45%;']
                    ],
                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 2],
                        'contentOptions' => ['style' => 'width: 30%;']

                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Action',
                        'contentOptions' => ['style' => 'width: 25%;'],
                        'buttons' => [

                            'view' => function ($url, $model1, $key) {
                                $urlConfig = [];

                                foreach ($model1->primaryKey() as $pk) {
                                    $urlConfig['id'] = $model1->$pk;
                                }
//                                        $urlConfig['simId'] = $model->simulation_id;
//                                        $urlConfig['bulan'] = $model->bulan;
//                                        $urlConfig['tahun'] = $model->tahun;

                                $url = Url::toRoute(array_merge(['/simulation-detail/view-asli'], $urlConfig));
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
                                foreach ($model->primaryKey() as $pk) {
                                    $urlConfig['id'] = $model->$pk;
                                }

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
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
