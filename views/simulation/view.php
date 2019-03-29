<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model projection\models\Simulation */

$this->title = 'Simulation'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Simulations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="simulation-view">

    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'start_date',
            'end_date',
            'effective_date',
            'created_at',
            'updated_at',
        ],

    ]) ?>
        </div>
    </div>
</div>



<div class="pe-detail-view">
    <div class="box">
        <div class="box-header"></div>

        <div class="box-body">

            <h4>Detail Simulation</h4>

            <table class="table table-striped">

                <th>No</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Element</th>
                <th>Amount</th>

                <?php
                $no = 0;
                $formatter = \Yii::$app->formatter;

                foreach ($simulationDetail as $rows) {
                    $no++;
                    ?>
                    <tr>
                        <td width="6%"><?= $no ?></td>
                        <td width="6%"><?= $rows->bulan ?></td>
                        <td width="6%"><?= $rows->tahun ?></td>
                        <td width="6%"><?= $rows->element ?></td>
                        <td width="6%"><?= $formatter->asDecimal(Html::encode($rows->amount), 2) ?></td>
                    </tr>
                <?php } ?>
            </table>


        </div>
    </div>
</div>
