<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model reward\models\RewardLog */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Reward Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-log-view">

    <div class="box">
        <div class="box-body">

<!--    <p>
        <?/*= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) */?>
        <?/*= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
    </p>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'user',
            'description',
            'created_at',
            'updated_at',
        ],
    ]) ?>
        </div>
    </div>

</div>
