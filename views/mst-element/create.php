<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\MstElement */

$this->title = 'Create Element';
$this->params['breadcrumbs'][] = ['label' => 'Mst Elements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-element-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
