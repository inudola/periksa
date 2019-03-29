<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model projection\models\SimulationDetail */

$this->title = 'Create Simulation Detail';
$this->params['breadcrumbs'][] = ['label' => 'Simulation Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simulation-detail-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
