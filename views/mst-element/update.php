<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\MstElement */

$this->title = 'Update Element: ' . $model->element_name;
$this->params['breadcrumbs'][] = ['label' => 'Element', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->element_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mst-element-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
