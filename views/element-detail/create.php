<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\ElementDetail */

$this->title = 'Create Element Detail';
$this->params['breadcrumbs'][] = ['label' => 'Element Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-detail-create">

    <?= $this->render('_form', [
        'model' => $model,
        'params'    => $params,
    ]) ?>

</div>
