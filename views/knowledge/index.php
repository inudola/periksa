<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\BaseStringHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KnowledgeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Knowledges';
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = Yii::$app->user->identity->employee->isAdmin;
?>
<div class="knowledge-index">
    <div class="box">
        <div class="box-header">
        </div>
        <div class="box-body">
            <?php Pjax::begin(); ?>
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

            
            <p>
                <?php 
                if ($isAdmin) { 
                echo Html::a('Create Knowledge', ['create'], ['class' => 'btn btn-success']);
                } ?>
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

                    'name',
                    [
                        'attribute' => 'description',
                        'format' => 'html',
                        //'label' => 'Description',
                        'value' => function ($dataProvider) {
                            $url = $dataProvider->description;
                            return BaseStringHelper::truncateWords($url, 15, null, true);
                        },
                    ],

                    ['class' => 'yii\grid\ActionColumn',
                        'visible' => $isAdmin && !Yii::$app->user->isGuest
                    ],
                ],


            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
