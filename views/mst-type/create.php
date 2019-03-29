<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model projection\models\MstType */

$this->title = 'Create MST Type';
$this->params['breadcrumbs'][] = ['label' => 'MST Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
