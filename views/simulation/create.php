<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model projection\models\Simulation */

$this->title = 'Create Simulation';
$this->params['breadcrumbs'][] = ['label' => 'Simulations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simulation-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
