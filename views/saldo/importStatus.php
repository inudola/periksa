<?php
/**
 * Created by PhpStorm.
 * User: SYSTEM
 * Date: 8/8/2018
 * Time: 2:37 PM
 */

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Import result for Saldo Nki ';
$this->params['breadcrumbs'][] = ['label' => 'Saldo Nki', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-danger berita-acara-import-result-view">
    <div class="box-body">
        <div class="box-header with-border">
            <?= Html::a('<i class="fa fa-address-card"></i>&nbsp;&nbsp;&nbsp;Saldo Nki', ['saldo/index'], ['class' => 'btn btn-info']) ?>
        </div>

        <?php
        if (count($rowsError) > 0) {
            ?>
            <div class="callout callout-danger">
                <h4>"Employee Not Found" Errors:</h4>

                <p>Data Row Number: <?= implode(', ', $rowsError) ?></p>
            </div>
            <?php
        }
        ?>

        <?php if (intval($failCount) > 0)  {?>
            <div class="callout callout-danger">
                <h4>Insert Errors:</h4>

                <p>Num. of data: <?= $failCount ?></p>
            </div>
        <?php } ?>

        <?php if (intval($successCount) > 0)  {?>
            <div class="callout callout-success">
                <h4>Number of data successfully imported:</h4>

                <p>Num. of data: <?= $successCount ?></p>
            </div>
        <?php } ?>
    </div>
</div>
