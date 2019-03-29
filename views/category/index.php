<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <div class="box">
        <div class="box-body">

            <p>
                <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?php Pjax::begin(); ?>
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

                    'category_name',
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
                    'categoryType.name',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
