<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel reward\models\RewardLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reward Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-log-index">

    <div class="box">
        <div class="box-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'user',
                    'description',
                    'created_at',
                    'updated_at',

                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>
