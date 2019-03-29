<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel reward\models\InsentifSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insentif';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary insentif-index">

    <div class="box-body">
        <div class="panel box box-warning insentif-index">
            <div class="box-header with-border">
                <h3 class="box-title">Import From Excel</h3>
            </div>

            <div class="box-body">

                <?php $uploadForm = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data'],
                    'action' => Url::to(['saldo/import']),
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
                        Url::to('@web/template/sample_insentifimport.xlsx'),
                        [
                            'class' => 'btn btn-info',
                        ])
                    ?>

                    <?= Html::submitButton('Bulk Import', ['class' => 'btn btn-primary']); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <hr/>

        <p>
            <?= Html::a('Add', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'options' => [
                'class' => 'table-responsive',
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn',
                    'contentOptions' => ['style' => 'width: 5%;']
                ],
                [
                    'attribute' => 'nik',
                    'contentOptions' => ['style' => 'width: 15%;']
                ],
                [
                    'attribute' => 'bi',
                    'contentOptions' => ['style' => 'width: 10%;']
                ],
                [
                    'attribute' => 'band',
                    'contentOptions' => ['style' => 'width: 10%;']
                ],
                [
                    'attribute' => 'smt',
                    'contentOptions' => ['style' => 'width: 10%;']
                ],
                [
                    'attribute' => 'tahun',
                    'contentOptions' => ['style' => 'width: 10%;']
                ],
                [
                    'attribute' =>  'nkk',
                    'contentOptions' => ['style' => 'width: 10%;']
                ],
                [
                    'attribute' =>  'nku',
                    'contentOptions' => ['style' => 'width: 10%;']
                ],
                [
                    'attribute' =>  'nki',
                    'contentOptions' => ['style' => 'width: 10%;']
                ],


                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
