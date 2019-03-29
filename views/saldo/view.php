<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model projection\models\SaldoNki */

$this->title = "NIK : " . $model->nik;
$this->params['breadcrumbs'][] = ['label' => 'Saldo Nkis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saldo-nki-view">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'nik',
                    'bi',
                    'smt',
                    'tahun',
                    'total',
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
