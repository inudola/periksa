<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model projection\models\SaldoNki */

$this->title = 'Update Saldo NKI: ' . $model->nik;
$this->params['breadcrumbs'][] = ['label' => 'Saldo Nkis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="saldo-nki-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
