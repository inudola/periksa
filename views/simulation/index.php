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


    <div class="box box-default color-palette-box">
        <div class="box-header with-border">
            <div class="col-lg-4" style="margin-top:-10px;">
                <hr style="border-top:0;">
                <?= Html::a('<i class="fa fa-pencil-square-o"></i>' . ' Create Simulation', ['/simulation/create'], ['class' => 'btn btn-success']); ?>
            </div>

            <?php if (!empty($list)) { ?>
                <div class="col-lg-4" style="margin-top:-10px;">
                    <h4>Pilih Simulation</h4>
                    <?php if (!empty($list)) { ?>
                        <?= Html::dropDownList('list', null, $list, ['class' => 'dependent-input form-control', 'id' => 'list', 'data-next' => 'alternatif_id', 'prompt' => '- Select Simulation -']) ?>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="col-lg-4" style="margin-top:-10px;">
                <h4>Pilih Alternatif</h4>
                <?= Html::dropDownList('alternatif', null, [], ['class' => 'dependent-input form-control', 'id' => 'alternatif_id', 'data-next' => '', 'prompt' => '- Select Simulation dulu-']) ?>
            </div>
        </div>

    </div>
    <!-- /.box -->

    <div class="row">
        <div id="tabel">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-id-card"></i>
                        <h3 class="box-title">Original Budget</h3>
                    </div>
                    <!-- /.box-header -->
                    <div style="margin-top: -10px">
                        <div class="box-body">
                            <?php if (!empty($list) && !empty($dataProvider)) { ?>
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
                                                Html::a('<i class="fa fa-spinner"></i>'. ' Regenerate', ['/batch-entry/generate-saldo', 'simId' => $query1->id], [
                                                    'data' => [
                                                        'confirm' => 'Are you sure you want to process?',
                                                        'method' => 'post',

                                                    ],
                                                    //'id' => 'btnSubmit',
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
                                                    ['/batch-entry/add-batch',
                                                        'simId' => $model->simulation_id,
                                                        'bulan' => $model->bulan,
                                                        'tahun' => $model->tahun,
                                                        'mode'  => 'ORIGINAL BUDGET'
                                                    ],
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
                                        'before' => Html::a('<i class="fa fa-plus"></i>' . ' Add Element', ['/batch-entry/create-batch', 'simId' => $query->simulation_id], ['class' => 'btn btn-primary', 'title' => ('Create Batch Entry')]),
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
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.row -->


<?php
$urlData = Url::to(['simulation/get-data-tabel']);

$js = <<<js

        $("#list").on("change",function(){
$.ajax({
url:"{$urlData}",
type: "GET",
data: {id:$(this).val(), mode:''},
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


$jsOpt = '$("#alternatif_id").on("change", function() {
	var value = $(this).val(),
	    text = $("#alternatif_id option:selected").text()

	$.ajax({
		url: "' . Url::to(['simulation/get-data-tabel']) . '",
		data: {id: value, mode: text},
	
		success: function(data) {
			$("#tabel").html(data);
		}
	});
});';
$this->registerJs($jsOpt);


$jsAlt = '$(".dependent-input").on("change", function() {
	var value = $(this).val(),
	    text = $(this).text()
		obj = $(this).attr("id"),
		next = $(this).attr("data-next");
	
	$.ajax({
		url: "' . Yii::$app->urlManager->createUrl('monitoring/get-alternatif') . '",
		data: {value: value, obj: obj},
		type: "POST",
		success: function(data) {
			$("#" + next).html(data);
		}
	});
});';
$this->registerJs($jsAlt);
?>