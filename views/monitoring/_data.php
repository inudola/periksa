<?php

use kartik\grid\GridView;
use miloschuman\highcharts\Highcharts;
use reward\models\SimulationDetail;
//use yii\grid\GridView;
use reward\models\SimulationDetailSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use reward\models\PayrollResult;
use reward\models\PayrollResultSearch;

$this->title = 'Projection Monitoring';
//$this->params['breadcrumbs'][] = $this->title;

?>

    <div class="simulation-detail-index">


        <!--box grafik-->
        <div class="box box-default color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-line-chart"></i> Comparison Grafik</h3>

                <h4 class="text-center">
                    <?php
                    if ($tahunAkhirSimulation == $currentReal) { ?>
                    Realization <?= $currentYear ?> &
                    <?php } ?>
                    Projection
                    <?= date("d-M-Y", strtotime($query->simulation->start_date)) . ' s/d ' . date("d-M-Y", strtotime($query->simulation->end_date)); ?>
                </h4>
                <h5 class="text-center">
                    <?php
                    if (!empty($mode)) { ?>
                        ORIGINAL BUDGET vs <?= $mode ?>
                    <?php } ?>
                </h5>
            </div>
            <div class="box-body">
                <div class="col">
                <?php
                echo
                Highcharts::widget([
                    'options' => [
                        'credits' => ['enabled' => false],
                        'chart' => [
                            'type' => 'line',
                        ],
                        'title' => ['text' => ''],
                        'xAxis' => [
                            //'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Des']
                            'title' => ['text' => 'Bulan'],
                            'categories' => $data['bulan'],

                        ],
                        'yAxis' => [
                            'title' => ['text' => 'Collection Data'],
                        ],
                        'series' =>
                            [
                                //['name' => 'Realization', 'data' => [1000, 0, 4000, 1000, 0, 4000, 5000, 7000, 3000, 1000, 0, 4000]],
                                ['name' => 'Realization', 'data' => $data['realization']],
                                ['name' => 'Projection', 'data' => $data['projection']],
                            ]
                    ],
                ]);

                ?>
                </div>
            </div>

            <div class="box-footer">
                <div class="row">

                    <!-- /.col -->
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <?php
                            if ($tahunAkhirSimulation == $currentReal) {
                                ?>
                                <h5 class="description-header">Rp <?= Yii::$app->formatter->asDecimal($sumRealization) ?></h5>
                                <span class="description-text">TOTAL REALIZATION</span><br>
                                <?= $currentReal ?>
                                <?php
                            } else {
                                ?>
                                <h5 class="description-header">0</h5>
                                <span class="description-text">TOTAL REALIZATION</span><br>
                                <?= $tahunAkhirSimulation ?>
                                <?php
                            }

                            ?>
                        </div>
                        <!-- /.description-block -->
                    </div>

                    <!-- /.col -->
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <h5 class="description-header"> <?= Yii::$app->formatter->asDecimal($sumProjection, 2) ?></h5>
                            <span class="description-text">TOTAL ORIGINAL BUDGET</span><br>
                            <?= date("d-M-Y", strtotime($query->simulation->start_date)) . ' s/d ' . date("d-M-Y", strtotime($query->simulation->end_date)); ?>
                        </div>
                        <!-- /.description-block -->
                    </div>


                    <!-- /.col -->
                    <div class="col-sm-4 col-xs-6">
                        <div class="description-block border-right">
                            <?php
                            if ($tahunAkhirSimulation == $currentReal) {
                                ?>
                                <h5 class="description-header">Rp <?= Yii::$app->formatter->asDecimal($gap, 2) ?></h5>
                                <span class="description-text">GAP ORIGINAL BUDGET - REALIZATION</span>

                                <?php
                            } else {
                                ?>
                                <h5 class="description-header">Rp <?= Yii::$app->formatter->asDecimal($sumProjection, 2) ?></h5>
                                <span class="description-text">GAP ORIGINAL BUDGET - REALIZATION</span>

                                <?php
                            }

                            ?>
                        </div>
                    </div>
                </div>
                <!-- /.row
            </div>
            <-- /.box-footer -->
            </div>
        </div>
        <!-- /.box grafik-->

        <div class="row">
            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-id-card"></i>

                        <?php
                        if ($tahunAkhirSimulation == $currentReal) { ?>
                        <h3 class="box-title">REALIZATION <?= $currentReal ?></h3>
                        <?php } else { ?>
                        <h3 class="box-title">REALIZATION <?= $tahunAkhirSimulation ?></h3>
                        <?php } ?>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">
                        <?php
                        if ($tahunAkhirSimulation == $currentReal) {?>

                            <?= GridView::widget([
                                'tableOptions' => [
                                    'class' => 'table table-striped',
                                ],
                                'options' => [
                                    'class' => 'table-responsive',
                                ],
                                'dataProvider' => $dataProviderReal,
                                //'filterModel' => $searchModel,
                                'export' => false,
                                'toolbar' => [

                                ],
                                'columns' => [
                                    ['class' => 'kartik\grid\SerialColumn'],
                                    [
                                        'class' => 'kartik\grid\ExpandRowColumn',
                                        'contentOptions' => ['style' => 'width: 5%;'],
                                        'value' => function ($model, $key, $index, $column) {
                                            return GridView::ROW_COLLAPSED;
                                        },
                                        'detail' => function ($model, $key, $index, $column) {

                                            $searchModel = new PayrollResultSearch();
                                            $searchModel->period_bulan = $model->period_bulan;
                                            $searchModel->period_tahun = $model->period_tahun;
                                            $searchModel->resource = $model->resource;
                                            $dataProvider = $searchModel->search1(Yii::$app->request->queryParams);
                                            $dataProvider->query->where(['period_bulan' => $model->period_bulan])
                                                ->andWhere(['period_bulan' => $model->period_bulan])
                                                ->andWhere(['resource' => 'Revex']);

                                            return Yii::$app->controller->renderPartial('detail-real', [
                                                'searchModel' => $searchModel,
                                                'dataProvider' => $dataProvider,
                                            ]);
                                        },
                                    ],
                                    [
                                        'attribute' => 'period_bulan',
                                        'contentOptions' => ['style' => 'width: 15%;']
//                        'value' => function ($model) {
//                            return $model->GetMonth();
//                        },
                                    ],

                                    [
                                        'attribute' => 'period_tahun',
                                        'contentOptions' => ['style' => 'width: 15%;']
                                    ],
                                    [
                                        'attribute' => 'sumReal',
                                        'format' => ['decimal', 2],
                                        'pageSummary' => true,


                                    ],
                                    // [
                                    //     'content' => function ($model) {
                                    //         return Html::a('<i class="fa fa-plus"></i>', ['/site/add-batch', 'simId' => $model->simulation_id, 'bulan' => $model->bulan, 'tahun' => $model->tahun], ['class' => 'btn btn-primary']);
                                    //     }

                                    // ],

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
                                'striped' => false,
                                'condensed' => false,

                            ]); ?>

                            <?php echo '
                            <h4>Keterangan</h4>
                            <h5>Data Realisasi diambil dari Oracle Telkomsel</h5>';
                        } else {
                            echo "Realization Not Found";
                        }

                        ?>

                    </div>

                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.realization -->

            <div class="col-md-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-id-card"></i>
                        <h3 class="box-title">ORIGINAL BUDGET</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <?= GridView::widget([
                            'tableOptions' => [
                                'class' => 'table table-striped',
                            ],
                            'options' => [
                                'class' => 'table-responsive',
                            ],
                            'dataProvider' => $dataProvider,
                            //'filterModel' => $searchModel,
                            'export' => false,
                            'toolbar' => [

                            ],
                            'columns' => [
                                [
                                    'class' => 'kartik\grid\SerialColumn',
                                    //'contentOptions' => ['style' => 'width: 5%;']
                                ],
                                [
                                    'class' => 'kartik\grid\ExpandRowColumn',
                                    'contentOptions' => ['style' => 'width: 5%;'],
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
                                            ->andwhere(['keterangan' => 'ORIGINAL BUDGET']);

                                        return Yii::$app->controller->renderPartial('detail', [
                                            'searchModel' => $searchModel1,
                                            'dataProvider1' => $dataProvider1,
                                        ]);
                                    },
                                ],

                                [
                                    'attribute' => 'bulan',
                                    'contentOptions' => ['style' => 'width: 15%;']

//                        'value' => function ($model) {
//                            return $model->GetMonth();
//                        },
                                ],
                                [
                                    'attribute' => 'tahun',
                                    'contentOptions' => ['style' => 'width: 15%;']
                                ],
                                [
                                    'attribute' => 'sumProj',
                                    'format' => ['decimal', 2],
                                    'pageSummary' => true,
                                    //'contentOptions' => ['style' => 'width: 15%;']

                                ],
                                // [
                                //     'content' => function ($model) {
                                //         return Html::a('<i class="fa fa-plus"></i>', ['/site/add-batch', 'simId' => $model->simulation_id, 'bulan' => $model->bulan, 'tahun' => $model->tahun], ['class' => 'btn btn-primary']);
                                //     }

                                // ],

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

                        ]); ?>
                    </div>

                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.original budget -->

        </div>

    </div>
