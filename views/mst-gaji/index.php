<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel reward\models\MstGajiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Gaji';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-gaji-index">
    <div class="box">
        <div class="box-body">


            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--            <p>
                <?/*= Html::a('Create Mst Gaji', ['create'], ['class' => 'btn btn-success']) */?>
            </p>-->

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
                    ['class' => 'yii\grid\SerialColumn'],

                    'bi',
                    [
                        'attribute' =>  'gaji_dasar',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'tunjangan_biaya_hidup',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'tunjangan_jabatan_struktural',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'tunjangan_jabatan_functional',
                        'format' => ['decimal', 2],
                    ],
                    [
                        'attribute' => 'tunjangan_rekomposisi',
                        'format' => ['decimal', 2],
                    ],


                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
