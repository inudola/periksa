<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel reward\models\SettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;

$detailTemplate = '{view}';

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
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'mstNature.nature_code',
                        'header' => 'Code'
                    ],

                    [
                        'attribute' => 'mstNature.nature_name',
                        'header' => 'Nature'
                    ],



                    ['class' => 'yii\grid\ActionColumn',
                        'buttons' => [

                            'view' => function ($url, $model, $key) {
                                $urlConfig = [];

                                $urlConfig['groupId'] = $model->group_nature;

                                $url = Url::toRoute(array_merge(['/setting/view-element'], $urlConfig));
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                                    $url, [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                        'class' => 'btn btn-sm btn-primary',
                                    ]);
                            },
                        ],
                        'template' => $detailTemplate
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
