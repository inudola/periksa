<?php
/**
 * Created by PhpStorm.
 * User: mn
 * Date: 11/08/2018
 * Time: 8:40
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Import Saldo NKI';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="saldo-nki-import">
    <div class="box">
        <div class="box-header">
            <h2>Grab Data</h2>
        </div>
        <div class="box-body">

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?= $form->field($modelImport, 'fileImport')->fileInput() ?>

            <?= Html::submitButton('Import', ['class' => 'btn btn-primary']); ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
