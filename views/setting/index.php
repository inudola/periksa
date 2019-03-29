<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel reward\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <div class="box">
        <div class="box-body">
            <?php Pjax::begin(); ?>
            <p>
                <?= Html::a('Create Setting', ['create'], ['class' => 'btn btn-success']) ?>
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

                    'setup_name',
                    'value_max',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == '1' ? 'Active' : 'Not Active';
                        }
                    ],
                    'description',


                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
