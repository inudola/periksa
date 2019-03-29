<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel reward\models\ElementDetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Element Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-detail-index">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--            <p>
                <?/*= Html::a('Create Element Detail', ['create'], ['class' => 'btn btn-success']) */?>
            </p>-->

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],


                    'band_individu',
                    'amount',
                    'mstElement.element_name',
                    'created_at',
                    //'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>