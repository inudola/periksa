<?php
/* @var $this yii\web\View */

use common\components\Helpers;
use reward\models\Setting;
use yii\helpers\Html;

$isAdmin = Yii::$app->user->identity->employee->isAdmin;
$isAdminProjection = Yii::$app->user->identity->employee->isAdminProjection;

//if ($isAdmin || $isAdminProjection) {
$this->title = 'Welcome to Reward Management App';
//}

//filter employee per user
$people = Yii::$app->user->identity->employee;

$formatter = \Yii::$app->formatter;


?>
<section class="content">
    <div class="content-responsive">
        <div class="col-sm-12 box-top-header-content">
        <div class="box box-custom box-widget">
        <div class="box-body">
        <div class="row-custom-header row col-sm-12">
        <div class="col-sm-6">
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
		<div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">
                                
                            </div>
                        </div>

                    </div>
					<div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">
                                
                            </div>
                        </div>

                    </div>
					<div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">
                                
                            </div>
                        </div>

                    </div>
					<div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">
                                
                            </div>
                        </div>

                    </div>
					<div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">
                                
                            </div>
                        </div>

                    </div>
					<div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">
                                
                            </div>
                        </div>

                    </div>
					<div class="col-sm-6 class-default">
                        <div class="box box-custom box-widget box-custom">
                            <div class="box-body" style="margin-right: 10px">
                                
                            </div>
                        </div>

                    </div>
</section>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
