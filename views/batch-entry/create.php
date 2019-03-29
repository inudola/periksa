<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model projection\models\BatchEntry */

$this->title = 'Create Batch Entry';
$this->params['breadcrumbs'][] = ['label' => 'Batch Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$simulationId = Yii::$app->getRequest()->getQueryParam('simId');
?>
<div class="batch-entry-create">

    <?= $this->render('_form', [
        'model' => $model,
        'model1' => $model1,
        'models' => $models,
    ]) ?>

</div>
