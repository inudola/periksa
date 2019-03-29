<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\CategoryType */

$this->title = 'Create Category Type';
$this->params['breadcrumbs'][] = ['label' => 'Category Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-type-create">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
