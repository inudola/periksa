<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
