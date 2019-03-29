<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Knowledge */

$this->title = 'Update Knowledge: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Knowledges', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="knowledge-update">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>

</div>
