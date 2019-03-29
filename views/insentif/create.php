<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\Insentif */

$this->title = 'Create Insentif';
$this->params['breadcrumbs'][] = ['label' => 'Insentifs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insentif-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
