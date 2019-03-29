<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model reward\models\MstGaji */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Mst Gajis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-gaji-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'bi',
            'gaji_dasar',
            'tunjangan_biaya_hidup',
            'tunjangan_jabatan_struktural',
            'tunjangan_jabatan_functional',
            'tunjangan_rekomposisi',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
