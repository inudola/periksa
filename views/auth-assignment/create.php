<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EskSection */

$this->title = 'New User Assignment';
$this->params['breadcrumbs'][] = ['label' => 'Auth Assignment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-create">

    <div class="box box-danger color-palette-box">   
        <div class="box-body">   
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>    
</div>
