<?php

use yii\helpers\Html;
use yii\grid\GridView;
//use miloschuman\highcharts\Highcharts;
use dosamigos\highcharts\HighCharts;
use reward\models\SimulationDetail;

$this->title = 'Projection Monitoring';
//$this->params['breadcrumbs'][] = $this->title;
?>


<div class="simulation-detail-index">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <?= Html::a('Create Simulation', ['/simulation/create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?= GridView::widget([
                    'model' => $model,
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'simulation_id',
                    [
                        'attribute' => 'bulan',
                        'value' => function ($model) {
                            return $model->GetMonth();
                        }
                    ],
                    'tahun',
                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 3],

                    ],
                    [
                        'content' => function($model) {
                            return Html::a('<i class="fa fa-plus"></i>', ['/batch-entry/add', 'id' => 1], ['class' => 'btn btn-primary']);
                        }
                    ],
                    ['class' => 'yii\grid\ActionColumn'],

                ],
            ]); ?>
        </div>
    </div>
</div>
