<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel projection\models\SaldoNkiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Saldo NKI';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary saldo-nki-index">
    <div class="box-body">

        <div class="panel box box-warning saldo-nki-index">
            <div class="box-header with-border">
                <h3 class="box-title">Import From Excel</h3>
            </div>

            <div class="box-body">

                <?php $uploadForm = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data'],
                    'action' => Url::to(['saldo/import']),
                ]) ?>

                <div class="form-group">
                    <?= $uploadForm->field($uploadModel, 'userFile')->fileInput()->label('Excel File') ?>
                </div>

                <div class="form-group">
                    <?= $uploadForm->field($uploadModel, 'overwrite')->checkbox() ?>
                </div>

                <div class="form-group">
                    <?= Html::a(
                        '<span class="glyphicon glyphicon-cloud-download"></span> Sample',
                        Url::to('@web/template/sample_saldoimport.xlsx'),
                        [
                            'class' => 'btn btn-info',
                        ])
                    ?>

                    <?= Html::submitButton('Bulk Import', ['class' => 'btn btn-primary']); ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <hr/>

        <p>
            <?= Html::a('Add', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-striped',
            ],
            'options' => [
                'class' => 'table-responsive',
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'nik',
                'bi',
                'smt',
                'tahun',
                'total',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
</div>

