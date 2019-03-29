<?php

use kartik\export\ExportMenu;
use kartik\widgets\DepDrop;
use kartik\alert\Alert;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reward\models\MstType;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use reward\components\Helpers;
use fedemotta\datatables\DataTables;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model projection\models\BatchEntry */

$this->title = 'Batch Entry';
$this->params['breadcrumbs'][] = ['label' => 'Batch Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$simulationId = Yii::$app->getRequest()->getQueryParam('simId');
$bulan = Yii::$app->getRequest()->getQueryParam('bulan');
$tahun = Yii::$app->getRequest()->getQueryParam('tahun');

//$detailTemplate = '{view}, {update}, {delete}';
$detailTemplate = '{view}';
$detailTemplates = '{view}, {export}';

$catList = [
    'PAYROLL' => 'PAYROLL',
    'NON PAYROLL' => 'NON PAYROLL'
];

$keteranganList = [
    'ORIGINAL BUDGET' => 'ORIGINAL BUDGET',
    'ALTERNATIF 1' => 'ALTERNATIF 1',
    'ALTERNATIF 2' => 'ALTERNATIF 2',
    'ALTERNATIF 3' => 'ALTERNATIF 3',
    'ALTERNATIF 4' => 'ALTERNATIF 4',
    'ALTERNATIF 5' => 'ALTERNATIF 5',
];

?>
    <div class="batch-entry-form">

        <!-- alert message for parent ID-->
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed type, please enter type first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-type', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed field jumlah orang, please enter first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-jml-orang', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed field keterangan, please select first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-keterangan', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed bi, please enter bi first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-bi', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed bp, please enter bp first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-bp', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "Some data doesn't have proposed field bp tujuan, please select first!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-bp-tujuan', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "bi must be greater than or equals bp!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-prom-rot', 'style' => 'display:none;'],
        ]);
        ?>
        <?php
        echo Alert::widget([
            'type' => Alert::TYPE_WARNING,
            'title' => 'Warning!',
            'icon' => 'glyphicon glyphicon-exclamation-sign',
            'body' => "not one band!",
            'showSeparator' => false,
            'delay' => false,
            'options' => ['id' => 'msg-parent-bi-bp', 'style' => 'display:none;'],
        ]);
        ?>

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">New Recruitment</a></li>
                <li><a href="#tab_2" data-toggle="tab">New Element</a></li>
                <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-calendar"></i>
                        Bulan <?= $bulan . ' ' . $tahun ?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1" style="color: #0f0f0f">
                    <div class="box-body">
                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model1, 'simulation_id')->hiddenInput(['value' => $simulationId])->label(false); ?>
                        <?= $form->field($model1, 'bulan')->hiddenInput(['value' => $bulan])->label(false); ?>
                        <?= $form->field($model1, 'tahun')->hiddenInput(['value' => $tahun])->label(false); ?>

                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($model, 'type_id')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(MstType::findAll(['isYear' => MstType::MONTH]), 'id', 'type'),
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select type ...', 'id' => 'prohire-id'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($model, 'jumlah_orang')->textInput() ?>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($model1, 'keterangan')->dropDownList($keteranganList, ['prompt' => '', 'required' => true]); ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 type_trainee">
                                <?= $form->field($model, 'bi')->widget(Select2::classname(), [
                                    'data' => Helpers::getBiList(),
                                    'language' => 'en',
                                    'options' => ['prompt' => ''],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                            </div>

                            <div class="col-md-3 type_trainee">
                                <?= $form->field($model, 'bp')->widget(Select2::classname(), [
                                    'data' => Helpers::getBpList(),
                                    'language' => 'en',
                                    'options' => ['prompt' => ''],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                            </div>

                            <div class="col-md-3 type_promosi" style="display:none;">
                                <?= $form->field($model, 'bp_tujuan')->widget(Select2::classname(), [
                                    'data' => Helpers::getBpList(),
                                    'language' => 'en',
                                    'options' => ['prompt' => ''],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                            </div>
                        </div>

                        <div class="row type_prohire" style="display:none;">
                            <div class="col-md-3">
                                <?= $form->field($model, 'perc_inc_gadas')->textInput() ?>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($model, 'perc_inc_tbh')->textInput() ?>
                            </div>
                        </div>

                        <div class="row type_prohire" style="display:none;">
                            <div class="col-md-3">
                                <?= $form->field($model, 'perc_inc_rekomposisi')->textInput() ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= Html::submitButton('Save', ['id' => 'btnSubmit', 'class' => 'btn btn-success']) ?>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                        <h5>(*) Isi <b>Keterangan</b> dengan nama alternatif</h5>
                    </div>
                </div>
                <!-- /.tab-pane -->


                <div class="tab-pane" id="tab_2" style="color: #0f0f0f">
                    <div class="box-body">

                        <?php $form = ActiveForm::begin([
//                            'id' => 'tabular-form',
//                            'enableAjaxValidation' => true,
//                            'enableClientValidation' => true,
//                            'validateOnChange' => false,
//                            'validateOnSubmit' => true,
//                            'validateOnBlur' => false,
                            'action' => Url::to(['simulation-detail/create']),
                        ]); ?>

                        <div class="row">
                            <?= $form->field($model1, 'simulation_id')->hiddenInput(['value' => $simulationId])->label(false); ?>
                            <?= $form->field($model1, 'bulan')->hiddenInput(['value' => $bulan])->label(false); ?>
                            <?= $form->field($model1, 'tahun')->hiddenInput(['value' => $tahun])->label(false); ?>

                            <!--<div class="col-md-3">
                                <?/*= $form->field($model, 'type_element')->dropDownList($catList, ['prompt' => '', 'id' => 'cat-id', 'required' => true]); */?>
                            </div>

                            <div class="col-md-3">

                                <?/*= $form->field($model1, 'element')->widget(DepDrop::classname(), [
                                    'options' => ['id' => 'subcat-id', 'required' => true],
                                    'pluginOptions' => [
                                        'depends' => ['cat-id'],
                                        'placeholder' => 'Select ...',
                                        'url' => Url::to(['/mst-element/lists'])
                                    ]
                                ]);
                                */?>
                            </div>-->

                            <div class="col-md-3">
                                <?= $form->field($model1, 'element')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(\reward\models\MstElement::find()->asArray()->all(), 'element_name', 'element_name'),
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);
                                ?>
                            </div>

                            <div class="col-md-3">
                                <?= $form->field($model1, 'n_group')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(\reward\models\MstNature::find()->asArray()->orderBy('nature_code')->where(['status' => 1])->all(), 'id', 'nature_name'),
                                    'language' => 'en',
                                    'options' => ['placeholder' => 'Select ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Group');
                                ?>
                            </div>

                            <div class="col-md-3">
                                <?=
                                $form->field($model1, 'amount')->widget(\yii\widgets\MaskedInput::className(), [

                                    'clientOptions' => [
                                        'alias' => 'numeric',
                                        'allowMinus' => false,
                                        'groupSize' => 3,
                                        'radixPoint' => ".",
                                        'groupSeparator' => '.',
                                        'autoGroup' => true,
                                        'removeMaskOnSubmit' => true,
                                        'required' => true
                                    ],


                                ]);
                                ?>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                                </div>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <!-- /.tab-pane -->

            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </div>


    <div class="batch-entry-create">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right">
                <li class="active"><a href="#tab_1-1" data-toggle="tab">Personnel Expense</a></li>

                <?php if ($mode == 'ORIGINAL BUDGET') { ?>
                    <li><a href="#tab_2-2" data-toggle="tab">Penyebab Kenaikan</a></li>
                <?php } ?>

                <li><a href="#tab_3-2" data-toggle="tab">Batch Entry</a></li>

                <li class="pull-left header"><i class="fa fa-th"></i> Detail
                    Bulan <?= $bulan . ' ' . $tahun . ' (' . $mode . ')' ?></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1" style="color: #0f0f0f">

                    <?= GridView::widget([
                        'dataProvider' => $getModel,
                        //'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'contentOptions' => ['style' => 'width: 5%;']
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

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'contentOptions' => ['style' => 'width: 25%;'],
                                'buttons' => [

                                    'view' => function ($url, $model, $key) {
                                        $urlConfig = [];

                                        foreach ($model->primaryKey() as $pk) {
                                            //$urlConfig['id'] = $model1->$pk;
                                            $urlConfig['simId'] = $model->simulation_id;
                                            $urlConfig['bulan'] = $model->bulan;
                                            $urlConfig['tahun'] = $model->tahun;
                                            $urlConfig['group'] = $model->n_group;
                                        }


                                        $url = Url::toRoute(array_merge(['view-group'], $urlConfig));
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
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2-2" style="color: #0f0f0f">

                    <?= GridView::widget([
                        'dataProvider' => $dataProv,
                        //'filterModel' => $searchModel,
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'contentOptions' => ['style' => 'width: 5%;']
                            ],

                            [
                                'attribute' => 'description',
                                'contentOptions' => ['style' => 'width: 40%;']
                            ],
                            [
                                'header' => 'Jumlah Karyawan',
                                'attribute' => 'amount',
                                'contentOptions' => ['style' => 'width: 30%;']
                            ],


                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'contentOptions' => ['style' => 'width: 25%;'],
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        $urlConfig = [];
                                        foreach ($model as $pk) {
                                            $urlConfig['simId'] = $model->simulation_id;
                                            $urlConfig['bulan'] = $model->bulan;
                                            $urlConfig['tahun'] = $model->tahun;
                                            $urlConfig['desc'] = $model->description;
                                        }

                                        $url = Url::toRoute(array_merge(['/batch-detail/view-batch-detail'], $urlConfig));
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                            $url, [
                                                'title' => 'View',
                                                'data-pjax' => '0',
                                                'class' => 'btn btn-sm btn-info',
                                            ]);
                                    },
                                    'export' => function ($url, $model, $key) {
                                        $urlConfig = [];
                                        foreach ($model->primaryKey() as $pk) {
                                            $urlConfig['id'] = $model->$pk;
                                        }

                                        $url = Url::toRoute(array_merge(['/batch-detail/export'], $urlConfig));
                                        return Html::a('<span class="glyphicon glyphicon-download-alt"></span>',
                                            $url, [
                                                'title' => 'Export',
                                                'data-pjax' => '0',
                                                'class' => 'btn btn-sm btn-default',
                                            ]);
                                    },


                                ],
                                'template' => $detailTemplates
                            ],
                        ],
                    ]); ?>

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_3-2" style="color: #0f0f0f">
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
                                'attribute' => 'batch.mstType.type',
                                'contentOptions' => ['style' => 'width: 12%;']
                            ],
                            [
                                'attribute' => 'batch.jumlah_orang',
                                'contentOptions' => ['style' => 'width: 10%;']
                            ],
                            [
                                'attribute' => 'batch.bi',
                                'contentOptions' => ['style' => 'width: 10%;']
                            ],
                            [
                                'attribute' => 'batch.bp',
                                'contentOptions' => ['style' => 'width: 10%;']
                            ],
                            [
                                'attribute' => 'batch.bp_tujuan',
                                'contentOptions' => ['style' => 'width: 10%;'],

                            ],
                            [
                                'attribute' => 'batch.perc_inc_gadas',
                                'value' => function ($model) {
                                    return $model->batch->perc_inc_gadas;
                                },
                                'contentOptions' => ['style' => 'width: 10%;']
                            ],
                            [
                                'attribute' => 'batch.perc_inc_tbh',
                                'value' => function ($model) {
                                    return $model->batch->perc_inc_tbh;
                                },
                                'contentOptions' => ['style' => 'width: 10%;']
                            ],
                            [
                                'attribute' => 'batch.perc_inc_rekomposisi',
                                'value' => function ($model) {
                                    return $model->batch->perc_inc_rekomposisi;
                                },
                                'contentOptions' => ['style' => 'width: 10%;']
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        $urlConfig = [];
                                        foreach ($model as $pk) {
                                            $urlConfig['simId'] = $model->simulation_id;
                                            $urlConfig['bulan'] = $model->bulan;
                                            $urlConfig['tahun'] = $model->tahun;
                                            $urlConfig['batch'] = $model->batch_id;
                                        }

                                        $url = Url::toRoute(array_merge(['/simulation-detail/view-batch-detail'], $urlConfig));
                                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                            $url, [
                                                'title' => 'View',
                                                'data-pjax' => '0',
                                                'class' => 'btn btn-sm btn-info',
                                            ]);
                                    },
                                    'update' => function ($url, $model, $key) {
                                        $urlConfig = [];
                                        //foreach ($model->primaryKey() as $pk) {
                                        $urlConfig['id'] = $model->batch_id;
                                        //}

                                        $url = Url::toRoute(array_merge(['/batch-entry/update'], $urlConfig));
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
                                        $urlConfig['simId'] = $model->simulation_id;
                                        $urlConfig['bulan'] = $model->bulan;
                                        $urlConfig['tahun'] = $model->tahun;
                                        $urlConfig['batch'] = $model->batch_id;
                                        //}

                                        $url = Url::toRoute(array_merge(['/simulation-detail/del'], $urlConfig));
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
                        'clientOptions' => [
                            'language' => [
                                'paginate' => ['previous' => 'Prev', 'next' => 'Next']
                            ],
                        ],
                    ]); ?>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>

    </div>

<?php
$js = <<< JS
$(document).ready(function () {
    $(document.body).on('change', '#prohire-id', function () {
        let val = $('#prohire-id option:selected').text();
            if(val !== 'PROHIRE' && val !== 'TRAINEE' && val !== 'PROMOSI' && val !== 'ROTASI') {
            $('.type_prohire').hide();
            $('.type_trainee').show();
            $('.type_promosi').hide();
            }
            else if(val == 'PROMOSI' || val == 'ROTASI') {
            $('.type_promosi').show();
            $('.type_trainee').show();
            }
            else if(val == 'TRAINEE') {
            $('.type_trainee').hide();
            $('.type_promosi').hide();
            }
            else {
            $('.type_prohire').show();
            $('.type_trainee').show();
            $('.type_promosi').hide();
            } 

    });
    
    $("#btnSubmit").click(function(){
            var type = $('#prohire-id option:selected').text();
            var bi = $('#batchentry-bi option:selected').val();
            var bp = $('#batchentry-bp option:selected').val();
            var keterangan = $('#simulationdetail-keterangan option:selected').val();
            var bpTujuan = $('#batchentry-bp_tujuan option:selected').val();
            
            var jmlOrang = $("#batchentry-jumlah_orang").val();
            
            var subBi = bi.substring(0, 1);
            var subBp = bp.substring(0, 1);
        
            if(type == 'Select type ...'){
                //show msg warning
                $("#msg-parent-type").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-type").fadeOut();
                }, 5000);
                return false;
            }
            else if(jmlOrang.length <= 0){
                //show msg warning
                $("#msg-parent-jml-orang").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-jml-orang").fadeOut();
                }, 5000);
                return false;
            } 
            else if(keterangan <= 0){
                //show msg warning
                $("#msg-parent-keterangan").fadeIn();
                setTimeout(function() {
                    $("#msg-parent-keterangan").fadeOut();
                }, 5000);
                return false;
            }
            else if(type !== 'TRAINEE'){
                if(bi <= 0){
                    //show msg warning
                    $("#msg-parent-bi").fadeIn();
                    setTimeout(function() {
                        $("#msg-parent-bi").fadeOut();
                    }, 5000);
                    return false;
                }
                else if(bp <= 0){
                    //show msg warning
                    $("#msg-parent-bp").fadeIn();
                    setTimeout(function() {
                        $("#msg-parent-bp").fadeOut();
                    }, 5000);
                    return false;
                }
                else if(type == 'PROMOSI' || type == 'ROTASI'){
                if(bi < bp){
                    //show msg warning
                    $("#msg-parent-prom-rot").fadeIn();
                    setTimeout(function() {
                        $("#msg-parent-prom-rot").fadeOut();
                    }, 5000);
                    return false;
                }
                else if(subBi !== subBp){
                    //show msg warning
                    $("#msg-parent-bi-bp").fadeIn();
                    setTimeout(function() {
                        $("#msg-parent-bi-bp").fadeOut();
                    }, 5000);
                    return false;
                }
                else if(bpTujuan <= 0){
                    //show msg warning
                    $("#msg-parent-bp-tujuan").fadeIn();
                    setTimeout(function() {
                        $("#msg-parent-bp-tujuan").fadeOut();
                    }, 5000);
                    return false;
                }
            } 
            }
           else{
                //click btnGenerate
                $('.se-pre-con').show();
                $("#form_generate").submit();
                return true;
            }
        });
});

JS;
$this->registerJs($js);