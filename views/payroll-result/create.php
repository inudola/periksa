<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model reward\models\PayrollResult */

$this->title = 'Create Payroll Result';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-result-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
