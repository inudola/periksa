<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Reward */

$this->title = $model->mstReward->reward_name;
$this->params['breadcrumbs'][] = ['label' => 'Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-view">

    <div class="box">

        <div class="box-body">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [

                    'emp_category',
                    'band_individu',
                    'band_position',
                    [
                        'attribute' => 'structural',
                        'value' => function ($model) {
                            return $model->structural == 'Y' ? 'Ya' : 'Tidak';
                        }],
                    [
                        'attribute' => 'functional',
                        'value' => function ($model) {
                            return $model->functional == 'Y' ? 'Ya' : 'Tidak';
                        }],
                    [
                        'attribute' => 'martital_status',
                        'value' => function ($model) {
                            if($model->marital_status == 'M'){
                                return 'Menikah';
                            }
                            else if($model->marital_status == 'S'){
                                return 'Single';
                            }
                            else {
                                return '';
                            }

                            return $model->marital_status;
                        }],
                    [
                        'attribute' => 'amount',
                        'format' => ['decimal', 2]
                    ],

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


