<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model admin\models\RewardCriteria */

$this->title = 'Create Reward Criteria';
$this->params['breadcrumbs'][] = ['label' => 'Reward Criterias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reward-criteria-create">

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
        'criteria' => $criteria,
        'params' => $params
    ]) ?>

</div>
