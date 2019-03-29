<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\BaseStringHelper;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\KnowledgeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Knowledges';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="knowledge-index">
    <div class="box">
        <div class="box-body">
            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'name',
                    [
                        'format' => 'html',
                        'label' => 'Description',
                        'value' => function ($dataProvider) {
                            $url = $dataProvider->description;
                            return BaseStringHelper::truncateWords($url, 15, null, true);
                        },
                    ],

//            ['class' => 'yii\grid\ActionColumn'],


                    ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
                ],
            ]);

            ?>


        </div>
    </div>
</div>
