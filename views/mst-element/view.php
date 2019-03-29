<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use fedemotta\datatables\DataTables;


/* @var $this yii\web\View */
/* @var $model reward\models\MstElement */

$this->title = $model->element_name;
$this->params['breadcrumbs'][] = ['label' => 'Element', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$detailTemplate = '{update}, {delete}';


?>
<div class="box box-primary mst-element-view">

    <div class="box-body">
        <div class="panel box box-warning mst-reward-view">

            <div class="box-header with-border">
                <h4 class="box-title">Master Element </h4>
            </div>

            <div class="box-body">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [

                        'element_name',
                        'type',
//                        [
//                            'attribute' => 'isYear',
//                            'value' => function ($model) {
//                                return $model->isYear == 'Y' ? 'Tahunan' : 'Bulanan';
//                            }
//                        ],
                        ['attribute' => 'status',
                            'value' => function ($model) {
                                return $model->status == '1' ? 'Active' : 'Not Active';
                            }
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>

                <br/>
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
            </div>
        </div>

    </div>
</div>