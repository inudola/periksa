<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;


/* @var $this yii\web\View */
/* @var $searchModel app\models\EskSectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Data';
$this->params['breadcrumbs'][] = ['label' => 'Access Lists'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-lists-index">
    <p>
        <?= Html::a('New User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box box-danger color-palette-box">   
        <div class="box-body">   
            <?php if($dataProvider->getCount() > 0) { ?>
            <?= DataTables::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'contentOptions'=>['style'=>'width: 5%;']
                    ],

                    [
                        'header' => 'NIK',
                        'value' => function($model) {
                            if(!empty($model->nik)){
                                return $model->nik;
                            }else{
                                return "-";
                            }
                        },
                        'contentOptions'=>['style'=>'width: 10%;']
                    ],
                    'username',
                    'email',

                    [
                        'format'=>'raw',
                        'value' => function($data){
                        return
                            Html::a('<span class="glyphicon glyphicon-pencil" style="color:green;"></span> ', ['update','id'=>$data->id], ['title' => 'edit']).' '.
                            Html::a('<span class="glyphicon glyphicon-trash" style="color:red;"></span> ', ['delete', 'id' => $data->id], [
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                        'contentOptions'=>['align' => 'center','style'=>'width: 10%;']
                    ],
                ],
                'clientOptions' => [
                    'language' => [
                        'paginate' => ['previous' => 'Prev', 'next' => 'Next']        
                    ], 
                ],
            ]);}else{ ?>
                <table id="datatables_w0" class="table table-striped table-bordered dataTable no-footer" width="100%" cellspacing="0">
                    <thead>
                        <tr><th>#</th><th>NIK</th><th>Username</th><th>Email</th><th></th></tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5"><div class="empty">No results found.</div></td></tr>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>  
</div>    

<!-- JS SCRIPT -->
<?php
$script = <<< JS
    $(document).ready(function(){
        setTimeout(function() {
            $("#w1-success").fadeOut();
            $("#w1-error").fadeOut();
            $("#w0-success").fadeOut();
            $("#w0-error").fadeOut();
        }, 4000);
    });
JS;

$this->registerJs($script);
?>    