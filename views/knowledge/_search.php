<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model admin\models\KnowledgeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<section class="content">
    <div class="error-page">
        <!--        <h2 class="headline text-yellow"> <i class="fa fa-search"></i></h2>-->
        <style>
            #btnn {
                margin-top: 9px;
            }
        </style>
        <div class="error-content">
            <h3><i class="fa fa-search text-yellow"></i> Find!</h3>

            <p>
                Find reward knowledge data using the search form.
            </p>

            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'options' => ['class' => 'search-form"'],
            ]); ?>

            <div class="input-group">
                <?= $form->field($model, 'name')->textInput()->input('text', ['placeholder' => "Enter Knowledge Name"])->label(false); ?>

                <div class="input-group-btn">
                    <?= Html::submitButton('GO!', ['class' => 'btn btn-warning btn-flat', 'id' => 'btnn']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>


