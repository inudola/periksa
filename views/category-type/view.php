<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model reward\models\CategoryType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Category Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-type-view">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
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
