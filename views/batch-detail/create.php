<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\BatchDetail */

$this->title = 'Create Batch Detail';
$this->params['breadcrumbs'][] = ['label' => 'Batch Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="batch-detail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
