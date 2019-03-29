<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EskSection */

$this->title = 'Update User Assignment Data: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Auth Assignment', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="auth-assignment-update">
    <div class="box box-danger color-palette-box">   
        <div class="box-body">  
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>            
</div>
