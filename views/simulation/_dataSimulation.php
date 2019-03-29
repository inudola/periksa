<?php

use reward\models\SimulationDetailSearch;
use yii\helpers\Url;
use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use reward\models\SimulationDetail;

$this->title = 'Projection Monitoring';
//$this->params['breadcrumbs'][] = $this->title;


?>

    <div class="simulation-detail-index">

        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-id-card"></i>
                    <h3 class="box-title">Original Budget</h3>
                </div>
                <!-- /.box-header -->


                <div class="box-body">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'export' => false,
                        'toolbar' => [
                            [

                                'content' =>

                                    Html::a('<i class="fa fa-exchange"></i>' . ' Export', ['//simulation/export', 'id' => $query1->id], [
                                        'class' => 'btn btn-success',
                                        'title' => 'Export To Excel',
                                        'data' => [
                                            'confirm' => 'Are you sure you want export to excel?',
                                            'method' => 'post',
                                        ],
                                    ]),

                                'options' => ['class' => 'btn-group mr-2'],

                            ],
                            [
                                'content' =>
                                    Html::a('<i class="fa fa-spinner"></i>' . ' Regenerate', ['/batch-entry/generate-saldo', 'simId' => $query->simulation_id], [
                                        'data' => [
                                            'confirm' => 'Are you sure you want to process?',
                                            'method' => 'post',


                                        ],
                                        'class' => 'btn btn-primary',
                                        'title' => ('Regenerate Simulation'),

                                    ]),

                                'options' => ['class' => 'btn-group mr-2'],
                            ],
                            [

                                'content' =>

                                    Html::a('<i class="fa fa-trash-o"></i>', ['//simulation/del', 'id' => $query1->id, 'mode' => 'ORIGINAL BUDGET'], [
                                        'class' => 'btn btn-danger',
                                        'title' => 'Delete Simulation',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ]),

                                'options' => ['class' => 'btn-group mr-2'],

                            ],

                            '{export}',
                            //'{toggleData}',
                        ],
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            [
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'value' => function ($model, $key, $index, $column) {
                                    return GridView::ROW_COLLAPSED;
                                },
                                'detail' => function ($model, $key, $index, $column) {
                                    $searchModel1 = new SimulationDetailSearch();
                                    $searchModel1->simulation_id = $model->simulation_id;
                                    $searchModel1->bulan = $model->bulan;
                                    $searchModel1->tahun = $model->tahun;
                                    $dataProvider1 = $searchModel1->search1(Yii::$app->request->queryParams);
                                    $dataProvider1->query->where(['simulation_id' => $model->simulation_id])
                                        ->andwhere(['bulan' => $model->bulan])
                                        ->andwhere(['tahun' => $model->tahun])
                                        ->andWhere(['NOT', ['n_group' => null]])
                                        ->andwhere(['keterangan' => 'ORIGINAL BUDGET']);

                                    return Yii::$app->controller->renderPartial('detail', [
                                        'searchModel' => $searchModel1,
                                        'dataProvider1' => $dataProvider1,
                                    ]);
                                },
                            ],

                            [
                                'attribute' => 'bulan',
                                'value' => function ($model) {
                                    return $model->GetMonth();
                                },
                                'contentOptions' => ['style' => 'width: 20%;']
                            ],
                            [
                                'attribute' => 'tahun',
                                'contentOptions' => ['style' => 'width: 20%;']
                            ],
                            [
                                'attribute' => 'sumProj',
                                'format' => ['decimal', 2],
                                'pageSummary' => true

                            ],
                            [
                                'content' => function ($model) {
                                    return Html::a('<span class="glyphicon glyphicon-plus-sign"></span>', ['/batch-entry/add-batch',
                                        'simId' => $model->simulation_id,
                                        'bulan' => $model->bulan,
                                        'tahun' => $model->tahun,
                                        'mode'  => 'ORIGINAL BUDGET'
                                    ], ['class' => 'btn btn-primary']);
                                }

                            ],

                            //['class' => 'yii\grid\ActionColumn'],
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
                        'panel' => [
                            'heading' => '',
                            'type' => 'success',
                            'before' => Html::a('<i class="fa fa-plus"></i>' . ' Add Element', ['/batch-entry/create-batch', 'simId' => $query->simulation_id], ['class' => 'btn btn-primary']),
                            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['class' => 'btn btn-default', 'title' => ('Reset Grid')]),
                            'showFooter' => false
                        ],
                        'bordered' => true,
                        'striped' => false,
                        'condensed' => false,

                    ]); ?>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-bullhorn"></i>

                    <h3 class="box-title">
                        <?php if (!empty($mode)) {
                            echo $mode;
                        } else {
                            echo 'Alternatif';
                        }
                        ?>
                    </h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">

                    <?php if (!empty($mode)) {
                        echo GridView::widget([
                            'tableOptions' => [
                                'class' => 'table table-striped',
                            ],
                            'options' => [
                                'class' => 'table-responsive',
                            ],
                            'dataProvider' => $dataProviderAlt,
                            //'filterModel' => $searchModel,
                            'export' => false,
                            'toolbar' => [
                                [

                                    'content' =>

                                        Html::a('<i class="fa fa-exchange"></i>' . ' Export', ['//simulation/export', 'id' => $query1->id], [
                                            'class' => 'btn btn-success',
                                            'title' => 'Export To Excel',
                                            'data' => [
                                                'confirm' => 'Are you sure you want export to excel?',
                                                'method' => 'post',
                                            ],
                                        ]),

                                    'options' => ['class' => 'btn-group mr-2'],

                                ],

                                [

                                    'content' =>

                                        Html::a('<i class="fa fa-trash-o"></i>', ['//simulation/del', 'id' => $query1->id, 'mode' => $mode], [
                                            'class' => 'btn btn-danger',
                                            'title' => 'Delete Simulation',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ]),

                                    'options' => ['class' => 'btn-group mr-2'],

                                ],

                                '{export}',
                                //'{toggleData}',
                            ],
                            'columns' => [
                                [
                                    'class' => 'kartik\grid\SerialColumn',
                                ],
                                [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'value' => function ($model, $key, $index, $column) {
                                        return GridView::ROW_COLLAPSED;
                                    },
                                    'detail' => function ($model, $key, $index, $column) {

                                        $request = Yii::$app->request;
                                        $modeParams = $request->get('mode');

                                        $searchModel1 = new SimulationDetailSearch();
                                        $searchModel1->simulation_id = $model->simulation_id;
                                        $searchModel1->bulan = $model->bulan;
                                        $searchModel1->tahun = $model->tahun;
                                        $dataProvider1 = $searchModel1->search1(Yii::$app->request->queryParams);
                                        $dataProvider1->query->where(['simulation_id' => $model->simulation_id])
                                            ->andwhere(['bulan' => $model->bulan])
                                            ->andwhere(['tahun' => $model->tahun])
                                            ->andwhere(['not', ['n_group' => null]])
                                            ->andWhere(['IN', 'keterangan' , [$modeParams, 'ORIGINAL BUDGET']]);

                                        return Yii::$app->controller->renderPartial('detail', [
                                            'searchModel' => $searchModel1,
                                            'dataProvider1' => $dataProvider1,
                                        ]);
                                    },
                                ],

                                [
                                    'attribute' => 'bulan',
                                    'contentOptions' => ['style' => 'width: 20%;'],
                                    'value' => function ($model) {
                                        return $model->GetMonth();
                                    },
                                ],
                                [
                                    'attribute' => 'tahun',
                                    'contentOptions' => ['style' => 'width: 20%;']
                                ],
                                [
                                    'header' => 'Total Alternatif',
                                    'attribute' => 'sumProj',
                                    'format' => ['decimal', 2],
                                    'pageSummary' => true,

                                ],
                                [
                                    'content' => function ($model) {
                                        $request = Yii::$app->request;
                                        $modeParams = $request->get('mode');

                                        return Html::a('<span class="glyphicon glyphicon-plus-sign"></span>', ['/batch-entry/add-batch',
                                            'simId' => $model->simulation_id,
                                            'bulan' => $model->bulan,
                                            'tahun' => $model->tahun,
                                            'mode' => $modeParams,
                                            ], ['class' => 'btn btn-primary']);
                                    }

                                ],

                                //['class' => 'yii\grid\ActionColumn'],
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
                            'panel' => [
                                'heading' => '',
                                'type' => 'success',
                                //'before' => Html::a('Create Batch Entry', ['/site/create-batch', 'simId' => $query->simulation_id], ['class' => 'btn btn-primary']),
                                'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['class' => 'btn btn-default', 'title' => ('Reset Grid')]),
                                'showFooter' => false,
                            ],
                            'bordered' => true,
                            'persistResize' => false,
                            'resizableColumns' => false,
                            'striped' => false,
                            'condensed' => false,

                        ]);

                    } elseif (!empty($findAlt)) {
                        echo "Select Alternative";
                    } else {
                        echo "No result";
                    }
                    ?>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->

    </div>


<?php
$js = <<< JS
$(document).ready(function () {
    
    $("#btnSubmit").click(function(){
        
        //click btnGenerate
            $('.se-pre-con').show();
            $("#form_generate").submit();
            
        });
});

JS;
$this->registerJs($js);