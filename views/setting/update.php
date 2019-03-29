<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\Setting */

$this->title = 'Update Setting: ' . $model->setup_name;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->setup_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="setting-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
