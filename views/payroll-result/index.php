<?php

use reward\models\PayrollResultSearch;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel reward\models\PayrollResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Realization';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="box box-primary payroll-result-index">
        <div class="box-body">

            <!--box list select option-->
            <div class="box-header with-border">
                <?php if (!empty($list)) { ?>
                    <div class="col-lg-offset-8">
                        <?php if (!empty($list)) { ?>
                            <?= Html::dropDownList('list', null, $list, ['class' => 'dependent-input form-control', 'id' => 'list', 'prompt' => '- Select Resource -']) ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <!-- /.box list select option-->

            <div class="card">
                <div id="data">
                    <div class="panel box box-warning saldo-nki-index">
                        <div class="box-header with-border">
                            <h3 class="box-title">Import From Excel</h3>
                        </div>

                        <div class="box-body">

                            <?php $uploadForm = ActiveForm::begin([
                                'options' => ['enctype' => 'multipart/form-data'],
                                'action' => Url::to(['payroll-result/import']),
                            ]) ?>

                            <div class="form-group">
                                <?= $uploadForm->field($uploadModel, 'userFile')->fileInput()->label('Excel File') ?>
                            </div>

                            <div class="form-group">
                                <?= $uploadForm->field($uploadModel, 'overwrite')->checkbox() ?>
                            </div>

                            <div class="form-group">
                                <?= Html::a(
                                    '<span class="glyphicon glyphicon-cloud-download"></span> Sample',
                                    Url::to('@web/template/sample_realimport.xlsx'),
                                    [
                                        'class' => 'btn btn-info',
                                    ])
                                ?>

                                <?= Html::submitButton('Bulk Import', ['class' => 'btn btn-primary']); ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                    <div class="box box-default">
                        <div class="box-header with-border">
                            <i class="fa fa-id-card"></i>
                            <h3 class="box-title">Realization From File Revex</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <?php if (!empty($list) && !empty($dataProvider)) { ?>
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
                                    'toolbar' => [],
                                    'columns' => [
                                        [
                                            'class' => 'kartik\grid\SerialColumn',
                                            'contentOptions' => ['style' => 'width: 5%;']
                                        ],
                                        [
                                            'class' => 'kartik\grid\ExpandRowColumn',
                                            'contentOptions' => ['style' => 'width: 5%;'],
                                            'value' => function ($model, $key, $index, $column) {
                                                return GridView::ROW_COLLAPSED;
                                            },
                                            'detail' => function ($model, $key, $index, $column) {

                                                $searchModel = new PayrollResultSearch();
                                                $dataProvider = $searchModel->search1($model->period_bulan);
                                                $dataProvider->query->where(['resource' => 'Revex']);

                                                return Yii::$app->controller->renderPartial('detail-real', [
                                                    'searchModel' => $searchModel,
                                                    'dataProvider' => $dataProvider,
                                                ]);
                                            },
                                        ],
                                        [
                                            'attribute' => 'period_bulan',
                                            'value' => function ($model) {
                                                return $model->GetMonth();
                                            },
                                            'contentOptions' => ['style' => 'width: 20%;']
                                        ],
                                        [
                                            'attribute' => 'period_tahun',
                                            'contentOptions' => ['style' => 'width: 20%;']
                                        ],
                                        [
                                            'attribute' => 'sumReal',
                                            'format' => ['decimal', 2],
                                            'pageSummary' => true,
                                            'contentOptions' => ['style' => 'width: 30%;']

                                        ],
                                        [
                                            'content' => function ($model) {
                                                return Html::a('<span class="glyphicon glyphicon-plus-sign"></span>',
                                                    ['/payroll-result/add-batch',
                                                        'bulan' => $model->period_bulan,
                                                        'tahun' => $model->period_tahun,
                                                        'resource' => $model->resource
                                                    ],
                                                    ['class' => 'btn btn-primary', 'title' => 'Add Batch Entry']);
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
                                    'striped' => false,
                                    'condensed' => false,

                                ]); ?>

                            <?php } else { ?>
                                <h4>No results found. </h4>
                            <?php } ?>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
            <!-- /.row -->


        </div>
    </div>

<?php
$urlData = Url::to(['payroll-result/get-data-tabel']);

$js = <<<js
$("#list").on("change",function(){
    let val = $('#list option:selected').text();
    //console.log(val)
    
$.ajax({
url:"{$urlData}",
type: "GET",
data: {mode:$(this).val()},

success:function(data){
$("#data").html(data);
}
});
});

js;

$this->registerJs($js);