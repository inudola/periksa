<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $model reward\models\BatchDetail */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Batch Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$bulan = Yii::$app->getRequest()->getQueryParam('bulan');
$tahun = Yii::$app->getRequest()->getQueryParam('tahun');

?>
<div class="batch-detail-view">
    <div class="box">
        <div class="box-body">

            <h4>Detail Kenaikan Bulan <?= $bulan . ' ' . $tahun ?></h4>

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
                        'contentOptions' => ['style' => 'width: 40%;']
                    ],

                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 2],
                        'contentOptions' => ['style' => 'width: 30%;']
                    ],

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
