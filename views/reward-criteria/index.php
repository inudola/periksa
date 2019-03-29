<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\RewardCriteriaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reward Criterias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-criteria-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Reward Criteria', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table table-striped',
        ],
        'options' => [
            'class' => 'table-responsive',
        ],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'reward.reward_name',
            'reward_id',
            'created_at',
            'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
