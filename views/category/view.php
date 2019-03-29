<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <div class="box">
        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
              
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
                    [
                        'attribute' => 'description',
                        'format' => 'html',
                    ],
                    'title',
                    'note',
                    [
                        'attribute' => 'icon',
                        'format' => 'html',
                        'value' => function ($data) {
                            return Html::img(Yii::getAlias('@web').'/'. $data['icon'],
                                ['width' => '70px']);
                        },
                    ],
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
            <br/>
            <p>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

        </div>
    </div>

</div>
