<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\CategoryType */

$this->title = 'Update Category Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Category Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-type-update">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
