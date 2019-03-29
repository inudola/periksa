<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Reward */
foreach ($reward as $title){
    $this->title = 'Add Details Reward : '. $title->reward_name;
}

$this->params['breadcrumbs'][] = ['label' => 'Rewards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-create">

    <?= $this->render('_form', [
        'model'     => $model,
        'models'    => $models,
        'params'    => $params,
        //rewardCriteria' => $rewardCriteria
    ]) ?>

</div>
