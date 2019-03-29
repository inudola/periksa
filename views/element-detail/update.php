<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\ElementDetail */

$this->title = 'Update Element Detail: ' . $model->mstElement->element_name;
$this->params['breadcrumbs'][] = ['label' => 'Element Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mstElement->element_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="element-detail-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
