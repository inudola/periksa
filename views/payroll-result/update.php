<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reward\models\PayrollResult */

$this->title = 'Update Payroll Result: ' . $model->element_name . ', Bulan : ' . $model->period_bulan . ' - ' . $model->period_tahun;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->element_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payroll-result-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
