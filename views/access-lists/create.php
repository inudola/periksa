<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EskSection */

$this->title = 'New User';
$this->params['breadcrumbs'][] = ['label' => 'User Data', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="esk-section-create">

    <div class="box box-danger color-palette-box">   
        <div class="box-body">   
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>    
</div>
