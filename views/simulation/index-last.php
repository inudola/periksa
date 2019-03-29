<?php

use kartik\grid\GridView;
use reward\models\SimulationDetailSearch;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel projection\models\SimulationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Simulations';
$this->params['breadcrumbs'][] = $this->title;

$detailTemplate = '{add},{view}';

?>

    <div class="box box-primary simulation-index">

        <div class="box-body">
            <div class="row" style="margin-bottom: 10px">
                <div class="col-lg-4 ">
                    <hr style="border-top:0;">
                    <?= Html::a('<i class="fa fa-pencil-square-o"></i>' . ' Create Simulation', ['/simulation/create'], ['class' => 'btn btn-success']); ?>
                </div>
                <div class="col-lg-4">

                </div>
                <?php if (!empty($list)) { ?>
                    <div class="col-lg-4" style="margin-top:-10px;">
                        <h4>Pilih Simulation</h4>
                        <?= Html::dropDownList('list', null, $list, ['class' => 'form-control', 'id' => 'list']) ?>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div id="tabel" style="margin-top: -10px">
            <!-- /.box -->
            <div class="box-body">
                <?php if (!empty($list && $dataProvider)) { ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'export' => false,
                        'toolbar' => [
                            [

                                'content' =>

                                    Html::a('<i class="fa fa-exchange"></i>' . ' Export', ['//simulation/export', 'id' => $query1->id, 'format' => 'excel'], [
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
                                    Html::a('<i class="fa fa-spinner"></i>', ['/batch-entry/generate-saldo', 'simId' => $query1->id], [
                                        'data' => [
                                            'confirm' => 'Are you sure you want to process?',
                                            'method' => 'post',

                                        ],
                                        'id' => 'btnSubmit',
                                        'class' => 'btn btn-primary',
                                        'title' => ('Regenerate Simulation'),

                                    ]),

                                'options' => ['class' => 'btn-group mr-2'],
                            ],
                            [

                                'content' =>

                                    Html::a('<i class="fa fa-trash-o"></i>', ['//simulation/del', 'id' => $query1->id], [
                                        'class' => 'btn btn-danger',
                                        'title' => 'Delete Simulation',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this item?',
                                            'method' => 'post',
                                        ],
                                    ]),

                                'options' => ['class' => 'btn-group mr-2'],

                            ],

                            //'{export}',
                            //'{toggleData}',
                        ],
                        'columns' => [
                            ['class' => '\kartik\grid\SerialColumn',
                                'contentOptions' => ['style' => 'width: 5%;']
                            ],
                            [
                                'class' => 'kartik\grid\ExpandRowColumn',
                                'contentOptions' => ['style' => 'width: 5%;'],
                                'value' => function ($model, $key, $index, $column) {
                                    return GridView::ROW_COLLAPSED;
                                },
                                'detail' => function ($model, $key, $index, $column) {
                                    $searchModel = new SimulationDetailSearch();
                                    $searchModel->simulation_id = $model->simulation_id;
                                    $searchModel->bulan = $model->bulan;
                                    $searchModel->tahun = $model->tahun;
                                    $dataProvider = $searchModel->search1(Yii::$app->request->queryParams);

                                    return Yii::$app->controller->renderPartial('detail', [
                                        'searchModel' => $searchModel,
                                        'dataProvider' => $dataProvider,
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
                                'pageSummary' => true,
                                'contentOptions' => ['style' => 'width: 30%;']

                            ],
                            [
                                'content' => function ($model) {
                                    return Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                        ['/batch-entry/add-batch', 'simId' => $model->simulation_id, 'bulan' => $model->bulan, 'tahun' => $model->tahun],
                                        ['class' => 'btn btn-primary', 'title' => 'Add Batch Entry']);
                                },

                            ],

//                                [
//                                    'class' => '\kartik\grid\ActionColumn',
//
//                                ],
//                                    [
//                                        'class' => 'kartik\grid\ActionColumn',
//                                        'contentOptions' => ['style' => 'width: 25%;'],
//                                        'buttons' => [
//
//                                            'add' => function ($url, $model, $key) {
//                                                $urlConfig = [];
//
//                                                $urlConfig['simId'] = $model->simulation_id;
//                                                $urlConfig['bulan'] = $model->bulan;
//                                                $urlConfig['tahun'] = $model->tahun;
//
//                                                $url = Url::toRoute(array_merge(['/batch-entry/add-batch'], $urlConfig));
//                                                return Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
//                                                    $url, [
//                                                        'title' => 'Add Batch Entry',
//                                                        'data-pjax' => '0',
//                                                        'class' => 'btn btn-sm btn-primary',
//                                                    ]);
//                                            },
//
//                                        ],
//                                        'template' => $detailTemplate
//                                    ],
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
                            'before' => Html::a('<i class="fa fa-plus"></i>', ['/batch-entry/create-batch', 'simId' => $query->simulation_id], ['class' => 'btn btn-primary', 'title' => ('Create Batch Entry')]),
                            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['class' => 'btn btn-default', 'title' => ('Reset Grid')]),
                            'showFooter' => false
                        ],
                        'bordered' => true,
                        'striped' => false,
                        'condensed' => false,


                    ]);

                    ?>
                <?php } else { ?>
                    <h4>No results found. </h4>
                <?php } ?>
            </div>
        </div>
    </div>


<?php
$urlData = Url::to(['simulation/get-data-tabel']);
$js = <<<js

        $("#list").on("change",function(){
$.ajax({
url:"{$urlData}",
type: "GET",
data:"id="+$(this).val(),
success:function(data){
$("#tabel").html(data);
}
});
});

$("#btnSubmit").click(function(){
    //click btnGenerate
    $('.se-pre-con').show();
    $("#form_generate").submit();
    });
js;

$this->registerJs($js);
?>