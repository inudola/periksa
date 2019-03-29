<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\Insentif */

$this->title = 'Update NIK: ' . $model->nik;
$this->params['breadcrumbs'][] = ['label' => 'Insentif', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nik, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="insentif-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
