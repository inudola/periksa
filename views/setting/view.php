<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model reward\models\Setting */

$this->title = $model->setup_name;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-view">
    <div class="box">
        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'setup_name',
                    'value_max',
                    [
                        'attribute' => 'status',
                        'value' => function ($model) {
                            return $model->status == '1' ? 'Active' : 'Not Active';
                        }
                    ],
                    'description',
                    [
                        'attribute' => 'mstNature.nature_name',
                        'label' => 'Group'
                    ],
                    'created_at',
                    'updated_at',
                ],
            ]) ?>

            <br>
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
