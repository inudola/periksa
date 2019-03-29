<?php

use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $model reward\models\MstReward */

$this->title = $model->reward_name;
$this->params['breadcrumbs'][] = ['label' => 'Reward', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$isApproval = Yii::$app->user->identity->employee->isApproval;

if ($isApproval) {
    $detailTemplate = '{update}, {delete}, {approve}';
} else {
    $detailTemplate = '{update}, {delete}';
}
$fieldExists = [];
foreach (\reward\components\Helpers::getCriteria() as $criterion => $desc) {
    $fieldExists[$criterion] = false;
}


?>
<div class="mst-reward-view">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Master Reward</a></li>
            <li><a href="#tab_2" data-toggle="tab">Reward Criteria</a></li>
            <li><a href="#tab_3" data-toggle="tab">Reward Details</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1" style="color: #0f0f0f">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'reward_name',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (1 == $model->status) {
                                    // active
                                    $ret = '<span class="label label-success">Active</span>';

                                } else {
                                    $ret = '<span class="label label-default">Not Active</span>';
                                }

                                return $ret;
                            }
                        ],

                        'category.category_name',
                        [
                            'attribute' => 'description',
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'formula',
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'file',
                            'format' => 'html',
                            'value' => function ($model) {
                                return Html::a('<i class = "fa fa-download">' . ' PDF', [
                                    'mst-reward/pdf',
                                    'id' => $model->id,
                                ], [
                                    'class' => 'btn btn-default',
                                    'target' => '_blank',
                                ]);
                            },
                            'visible' => !empty($model->file),
                        ],
                        [
                            'attribute' => 'icon',
                            'format' => 'html',
                            'value' => function ($data) {
                                return Html::img(Yii::getAlias('@web') . '/' . $data['icon'],
                                    ['width' => '70px']);
                            },
                        ],
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>


                <br>
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
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2" style="color: #0f0f0f">
                <h4 class="box-title">
                    <?= Html::a('<i class="fa fa-plus"></i>' . ' Add', ['reward-criteria/create', 'mst_reward_id' => $model->id], ['class' => 'btn btn-success']) ?>
                </h4>
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 6%">No</th>
                        <th style="width: 40%">Criteria Name</th>
                        <th>Action</th>
                    </tr>

                    <tr>
                        <?php
                        $no = 0;
                        foreach ($rewardCriteria

                        as $rows) {
                        $no++;
                        if (array_key_exists($rows->criteria_name, $fieldExists)) {
                            $fieldExists[$rows->criteria_name] = true;
                        }
                        ?>
                        <td><?= $no ?></td>
                        <td><?= $rows->criteria_name ?></td>
                        <td>
                            <p>
                                <?= Html::a('<i class="fa fa-edit"></i>', ['reward-criteria/update', 'id' => $rows->id], ['class' => 'btn btn-primary']) ?>
                                <?= Html::a('<i class="fa fa-close"></i>', ['reward-criteria/delete', 'id' => $rows->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </p>
                        </td>
                    </tr>

                    <?php }

                    ?>

                    </tbody>
                </table>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3" style="color: #0f0f0f">
                <?php
                if (!empty($rewardCriteria)) {
                ?>

                <h4 class="box-title">
                    <?= Html::a('<i class="fa fa-plus"></i>'.' Add', ['reward/create', 'mst_reward_id' => $model->id], ['class' => 'btn btn-success']) ?>
                </h4>

                <?= DataTables::widget([
                    'tableOptions' => [
                        'class' => 'table table-bordered striped',
                    ],
                    'options' => [
                        'class' => 'table-responsive',
                    ],
                    'dataProvider' => $dataProvider,
                    'showOnEmpty' => false,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'contentOptions' => ['style' => 'width: 5%;']
                        ],

                        [
                            'attribute' => 'band_individu',
                            'visible' => $fieldExists['band_individu'],
                            //'contentOptions' => ['style' => 'width: 25%;']
                        ],
                        [
                            'attribute' => 'band_position',
                            'visible' => $fieldExists['band_position'],
                        ],
                        [
                            'attribute' => 'band',
                            'visible' => $fieldExists['band'],
                        ],
                        [
                            'attribute' => 'marital_status',
                            'visible' => $fieldExists['marital_status'],
                        ],
                        [
                            'attribute' => 'emp_category',
                            'visible' => $fieldExists['emp_category'],
                        ],
                        [
                            'attribute' => 'structural',
                            'visible' => $fieldExists['structural'],
                        ],
                        [
                            'attribute' => 'functional',
                            'visible' => $fieldExists['functional'],
                        ],
                        [
                            'attribute' => 'gender',
                            'visible' => $fieldExists['gender'],
                            'value' => function ($model) {
                                return $model->gender == 'F' ? 'Perempuan' : 'Laki - laki';
                            }
                        ],
                        [
                            'attribute' => 'kota',
                            'visible' => $fieldExists['kota'],
                        ],
                        [
                            'attribute' => 'amount',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'isApproved',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if (1 == $model->isApproved) {
                                    // active
                                    $ret = '<span class="label label-success">Approved</span>';

                                } else if (-1 == $model->isApproved) {
                                    $ret = '<span class="label label-default">Pending</span>';
                                } else {
                                    $ret = '<span class="label label-danger">Rejected</span>';
                                }

                                return $ret;
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => 'Action',
                            'buttons' => [

                                'update' => function ($url, $model, $key) {
                                    $urlConfig = [];
                                    foreach ($model->primaryKey() as $pk) {
                                        $urlConfig['id'] = $model->$pk;
                                    }

                                    $url = Url::toRoute(array_merge(['/reward/update'], $urlConfig));
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                        $url, [
                                            'title' => 'Edit',
                                            'data-pjax' => '0',
                                            'class' => 'btn btn-sm btn-primary',
                                        ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    $urlConfig = [];
                                    foreach ($model->primaryKey() as $pk) {
                                        $urlConfig['id'] = $model->$pk;
                                    }

                                    $url = Url::toRoute(array_merge(['/reward/delete'], $urlConfig));
                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                        $url, [
                                            'title' => 'Delete',
                                            'data-pjax' => '0',
                                            'class' => 'btn btn-sm btn-danger btn-delete',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ]);
                                },
                                'approve' => function ($url, $model, $key) {
                                    $urlConfig = [];
                                    foreach ($model->primaryKey() as $pk) {
                                        $urlConfig['id'] = $model->$pk;
                                    }

                                    $url = Url::toRoute(array_merge(['/reward/approve'], $urlConfig));
                                    return Html::a('<span class="glyphicon glyphicon-check"></span>',
                                        $url, [
                                            'title' => 'Approve',
                                            'data-pjax' => '0',
                                            'class' => 'btn btn-sm btn-info',
                                        ]);
                                },
                            ],
                            'template' => $detailTemplate
                        ],
                    ],
                    'clientOptions' => [
                        'language' => [
                            'paginate' => ['previous' => 'Prev', 'next' => 'Next']
                        ],
                    ],
                ]); ?>

                <?php } ?>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
</div>

