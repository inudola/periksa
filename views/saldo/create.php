<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model projection\models\SaldoNki */

$this->title = 'Create Saldo NKI';
$this->params['breadcrumbs'][] = ['label' => 'Saldo Nkis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saldo-nki-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
