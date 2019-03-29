<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel reward\models\MstElementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Element';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-element-index">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Element', ['create'], ['class' => 'btn btn-success']) ?>
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
            ['class' => 'yii\grid\SerialColumn'],

            'element_name',
//            [
//                'attribute' => 'isYear',
//                'value' => function ($model) {
//                    return $model->isYear == 'Y' ? 'Tahunan' : 'Bulanan';
//                }
//            ],
            'type',
            ['attribute' => 'status',
                'value' => function ($model) {
                    return $model->status == '1' ? 'Active' : 'Not Active';
                }
            ],
            'created_at',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
        </div>
    </div>
</div>
