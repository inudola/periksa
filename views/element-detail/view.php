<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model reward\models\ElementDetail */

$this->title = $model->mstElement->element_name;
$this->params['breadcrumbs'][] = ['label' => 'Element Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-detail-view">
    <div class="box">
        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'band_individu',
                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 2]
                    ],
                    'mstElement.element_name',
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