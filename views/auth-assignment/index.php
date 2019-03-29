<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;


/* @var $this yii\web\View */
/* @var $searchModel app\models\EskSectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auth Assignment';
$this->params['breadcrumbs'][] = ['label' => 'Access Lists'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-lists-index">
    <p>
        <?= Html::a('New Assignment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box box-danger color-palette-box">   
        <div class="box-body">   
            <?php if($dataProvider->getCount() > 0) { ?>
            <?= DataTables::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'contentOptions'=>['style'=>'width: 5%;']
                    ],

                    [
                        'header' => 'Assignment',
                        'value' => function($model) {
                            if(!empty($model->item_name)){
                                return $model->item_name;
                            }else{
                                return "-";
                            }
                        },
                        'contentOptions'=>['align' => 'center','style'=>'width: 40%;']
                    ],
                    [
                        'header' => 'User',
                        'value' => function($model) {
                            return $model->user_id;
                        },
                        'contentOptions'=>['align' => 'center','style'=>'width: 40%;']
                    ],
                    [
                        'format'=>'raw',
                        'value' => function($data){
                        return
                            Html::a('<span class="glyphicon glyphicon-pencil" style="color:green;"></span> ', ['update','item_name'=>$data->item_name,'user_id' => $data->user_id], ['title' => 'edit']).' '.
                            Html::a('<span class="glyphicon glyphicon-trash" style="color:red;"></span> ', ['delete', 'item_name'=>$data->item_name,'user_id' => $data->user_id], [
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
                        <tr><th>#</th><th>Assignment</th><th>Username</th>><th></th></tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4"><div class="empty">No results found.</div></td></tr>
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