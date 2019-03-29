<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model reward\models\Insentif */

$this->title = "NIK : " . $model->nik;
$this->params['breadcrumbs'][] = ['label' => 'Insentifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insentif-view">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">


            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'nik',
                    'bi',
                    'band',
                    'organisasi_nku',
                    'tipe_organisasi',
                    'smt',
                    'tahun',
                    'nkk',
                    'nku',
                    'nki',
                    'created_at',
                    'updated_at',
                ],
            ]) ?>
        <hr>
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
