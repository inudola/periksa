<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RewardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reward';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-index">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <?= Html::a('Create Reward', ['create'], ['class' => 'btn btn-success']) ?>
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

                    'reward_name',
                    'emp_category',
                    'band_individu',
                    //'band_position',
                    //'structural',
                    //'functional',
                    //'marital_status',
                    //'gender',
                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 2],
                    ],
                    //'status',
                    //'created_at',
                    //'updated_at',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>  