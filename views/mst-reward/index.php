<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel reward\models\MstRewardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reward';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-reward-index">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
            <?php Pjax::begin(); ?>
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
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (1 == $model->status) {
                                // active
                                $ret = '<span class="label label-success">Active</span>';

                            } else {
                                $ret = '<span class="label label-default">Not Active</span>';
                            }

                            return $ret;
                        }
                    ],
                    'category.category_name',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
