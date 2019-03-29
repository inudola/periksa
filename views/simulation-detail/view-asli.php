<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model projection\models\SimulationDetail */

$this->title = $model->element . ' - ' . $model->GetMonth() . ' ' . $model->tahun;
$this->params['breadcrumbs'][] = ['label' => 'Simulation Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="simulation-detail-view">
    <div class="box">
        <div class="box-header"></div>
        <div class="box-body">


            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    [
                        'attribute' => 'bulan',
                        'value' => function ($model) {
                            return $model->GetMonth();
                        }
                    ],
                    'tahun',
                    'element',
                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 3],

                    ],
                    'keterangan'

                ],
            ]) ?>

            <hr/>
            <p>

                <?php if ($model->keterangan == 'ORIGINAL BUDGET') {
                    echo Html::a('<i class="fa fa-edit"></i>'.' Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-primary', 'title' => 'Change to alternative']);
                } else {
                    echo Html::a( '<i class="fa fa-clone"></i>' . ' Clone', ['update', 'id' => $model->id], ['class' => 'btn btn-primary', 'title' => 'Clone to next alternative']);
                } ?>

                <?= Html::a('<i class="fa fa-trash"></i>' . ' Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

        </div>
    </div>
</div>


