<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel projection\models\MstTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MST Type';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-type-index">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?php Pjax::begin(); ?>

            <p>
                <?= Html::a('Create MST Type', ['create'], ['class' => 'btn btn-success']) ?>
            </p>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'type',
                    [
                        'attribute' => 'isYear',
                        'value' => function ($model) {
                            return $model->isYear == 'Y' ? 'Tahunan' : 'Bulanan';
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
