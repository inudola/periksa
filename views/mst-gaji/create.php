<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\MstGaji */

$this->title = 'Create Mst Gaji';
$this->params['breadcrumbs'][] = ['label' => 'Mst Gajis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-gaji-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
