<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Knowledge */

$this->title = 'Create Knowledge';
$this->params['breadcrumbs'][] = ['label' => 'Knowledges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="knowledge-create">
    <div class="box">
        <div class="box-header">
        </div>
        <div class="box-body">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
