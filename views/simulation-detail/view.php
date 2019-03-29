<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $model reward\models\BatchDetail */

$this->title = $model->batch->mstType->type;
$this->params['breadcrumbs'][] = ['label' => 'Batch Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$bulan = Yii::$app->getRequest()->getQueryParam('bulan');
$tahun = Yii::$app->getRequest()->getQueryParam('tahun');

?>
<div class="batch-detail-view">
    <div class="box">
        <div class="box-body">

            <h4>Detail Batch <?= ucfirst(strtolower($this->title)) ?> Bulan <?= $bulan . ' ' . $tahun ?></h4>

            <?= DataTables::widget([
                'tableOptions' => [
                    'class' => 'table table-striped',
                ],
                'options' => [
                    'class' => 'table-responsive',
                ],
                'dataProvider' => $dataProvider,
                'showOnEmpty' => false,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'contentOptions' => ['style' => 'width: 5%;']
                    ],

                    [
                        'attribute' => 'element',
                        'contentOptions' => ['style' => 'width: 30%;']
                    ],

                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 2],
                        'contentOptions' => ['style' => 'width: 25%;']
                    ],
                    [
                        'attribute' => 'keterangan',
                        'contentOptions' => ['style' => 'width: 30%;']
                    ],


//                    [
//                        'class' => 'yii\grid\ActionColumn',
//                        'buttons' => [
//                            'view' => function ($url, $model, $key) {
//                                $urlConfig = [];
//                                foreach ($model->primaryKey() as $pk) {
//                                    $urlConfig['id'] = $model->$pk;
//                                }
//
//                                $url = Url::toRoute(array_merge(['/reward/view'], $urlConfig));
//                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
//                                    $url, [
//                                        'title' => 'View',
//                                        'data-pjax' => '0',
//                                        'class' => 'btn btn-sm btn-info',
//                                    ]);
//                            },
//                            'update' => function ($url, $model, $key) {
//                                $urlConfig = [];
//                                foreach ($model->primaryKey() as $pk) {
//                                    $urlConfig['id'] = $model->$pk;
//                                }
//
//                                $url = Url::toRoute(array_merge(['/reward/update'], $urlConfig));
//                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
//                                    $url, [
//                                        'title' => 'Edit',
//                                        'data-pjax' => '0',
//                                        'class' => 'btn btn-sm btn-success',
//                                    ]);
//                            },
//                            'delete' => function ($url, $model, $key) {
//                                $urlConfig = [];
//                                foreach ($model->primaryKey() as $pk) {
//                                    $urlConfig['id'] = $model->$pk;
//                                }
//
//                                $url = Url::toRoute(array_merge(['/reward/delete'], $urlConfig));
//                                return Html::a('<span class="glyphicon glyphicon-trash"></span>',
//                                    $url, [
//                                        'title' => 'Delete',
//                                        'data-pjax' => '0',
//                                        'class' => 'btn btn-sm btn-danger btn-delete',
//                                        'data' => [
//                                            'confirm' => 'Are you sure you want to delete this item?',
//                                            'method' => 'post',
//                                        ],
//                                    ]);
//                            }
//                        ],
//                        'template' => $detailTemplate
//                    ],
                ],
                'clientOptions' => [
                    'language' => [
                        'paginate' => ['previous' => 'Prev', 'next' => 'Next']
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>
