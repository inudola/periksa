<?php

use common\components\Helpers;
use yii\helpers\Html;

$people = Yii::$app->user->identity->employee;
$session = Yii::$app->session;
/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">


        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>


        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li>
                    <div class="input-group margin header-search" style="height: 10px !important;width: 300px; margin: 0.534em !important;">
                        <input type="text" class="form-control input-sm" id="txt-search" placeholder="Search..."  autocomplete="off" style="padding-top: 9px; font-size: 10pt;">
                        <span class="input-group-btn">
                                <button type="button" class="btn btn-flat btn-search-custom"><i
                                            class="fa icon-cog fa-search"></i></button>
                            </span>
                    </div>
                </li>

                <?php
                if (!$session->get('isAdmin') || !$session->get('isAdminProjection')) {
                    ?>

                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <!--<img src="<? /*= Yii::getAlias('@web') */ ?> . /img/avatar-user.png" class="user-image"
                                 alt="User Image">-->
                            <!--<img src="<? /*= Helpers::PICTURE_URL . $people->person_id */ ?>" class="user-image" alt="User Image">-->
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->

                            <span class="hidden-xs">
                            <td><b>Welcome,</b> <?= $people->nama ?></td>
                        </span>
                        </a>
                        <ul class="dropdown-menu arrow_box" style="width: 220px !important; margin-top: 2px;background-color: #222d32;border-top-left-radius: 4px; border-top-right-radius: 4px;">
                            <!-- Menu Footer-->
                            <li class="user-footer" style="background-color: #222d32;">
                                <div class="pull-left" >
                                    <?= Html::a(
                                        '<i class="glyphicon glyphicon-log-out text-danger" style="color:#bebebe;"></i>&nbsp;&nbsp;&nbsp;Sign Out Application',
                                        ['/site/logout'],
                                        ['data-method' => 'post', 'title' => 'Sign Out Application', 'style' => 'font-size:14px;color:#bebebe;']
                                    ) ?>
                                </div>
                            </li>
                        </ul>
                    </li>

                <?php } else { ?>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle">
                        <span class="hidden-xs">
                            <td><b>Welcome,</b> <?= $people->nama ?></td>
                        </span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>

<!-- CSS SCRIPT -->
<?php
$script = <<< CSS
    .arrow_box {
        position: relative;
        background: #222d32;
        border: 4px solid #222d32;
    }
    .arrow_box:after, .arrow_box:before {
        bottom: 100%;
        left: 90%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
    }

    .arrow_box:after {
        border-color: rgba(34, 45, 50, 0);
        border-bottom-color: #222d32;
        border-width: 5px;
        margin-left: -5px;
    }
    .arrow_box:before {
        border-color: rgba(34, 45, 50, 0);
        border-bottom-color: #222d32;
        border-width: 11px;
        margin-left: -11px;
    }
    .skin-red .main-header .navbar{
        background-image: linear-gradient(to right, #d73925, #d00202)
    }
CSS;

$this->registerCss($script);
?>
