<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model projection\models\MstType */

$this->title = 'Update MST Type: ' . $model->type;
$this->params['breadcrumbs'][] = ['label' => 'MST Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mst-batch-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
