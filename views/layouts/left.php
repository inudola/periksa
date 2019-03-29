<?php

use yii\helpers\Html;
use common\components\Helpers;

/* @var $this \yii\web\View */
/* @var $content string */
$people = Yii::$app->user->identity->employee;

$isAdmin = Yii::$app->user->identity->employee->isAdmin;

$isAdminProjection = Yii::$app->user->identity->employee->isAdminProjection;


$menus = [
    ['label' => 'MAIN MENU', 'options' => ['class' => 'header'], 'visible' => true],
    ['label' => 'My Reward', 'icon' => 'home', 'url' => ['site/index'], 'visible' => (!Yii::$app->user->isGuest)],

    ['label' => 'Monitoring', 'icon' => 'line-chart', 'url' => ['monitoring/index'], 'visible' => $isAdminProjection],
    ['label' => 'Compare', 'icon' => 'exchange', 'url' => ['monitoring/compare'], 'visible' => $isAdminProjection],

    ['label' => 'Simulation', 'icon' => 'list-alt', 'url' => ['simulation/index'], 'visible' => $isAdminProjection],

    ['label' => 'MASTER DATA', 'options' => ['class' => 'header'], 'visible' => $isAdmin],
    [
        'label' => 'Master',
        'icon' => 'table',
        'url' => '#',
        'items' => [
            ['label' => 'Category Type', 'icon' => 'pencil-square', 'url' => ['category-type/index'], 'visible' => $isAdmin],
            ['label' => 'Category', 'icon' => 'pencil-square', 'url' => ['category/index'], 'visible' => $isAdmin],
            ['label' => 'Knowledge', 'icon' => 'pencil-square', 'url' => ['knowledge/index'], 'visible' => (!Yii::$app->user->isGuest)],
            ['label' => 'Reward', 'icon' => 'pencil-square', 'url' => ['mst-reward/index'], 'visible' => $isAdmin],
            ['label' => 'Recruitment Type', 'icon' => 'pencil-square', 'url' => ['mst-type/index'], 'visible' => $isAdmin],
            ['label' => 'Element', 'icon' => 'pencil-square', 'url' => ['mst-element/index'], 'visible' => $isAdmin],
            ['label' => 'Saldo NKI', 'icon' => 'pencil-square', 'url' => ['saldo/index'], 'visible' => $isAdminProjection],
            ['label' => 'Insentif', 'icon' => 'pencil-square', 'url' => ['insentif/index'], 'visible' => $isAdminProjection],
            ['label' => 'Realization', 'icon' => 'pencil-square', 'url' => ['payroll-result/index'], 'visible' => $isAdmin],

        ],
        'visible' => (Yii::$app->user->can('sysadmin') || $isAdmin || $isAdminProjection),
    ],
    [
        'label' => 'Settings',
        'icon' => 'gears',
        'url' => '#',
        'items' => [
            ['label' => 'Setting Value', 'icon' => 'pencil-square', 'url' => ['setting/index'], 'visible' => $isAdmin],
            [
                'label' => 'Access Lists',
                'icon' => 'users',
                'url' => '#',
                'visible' => Yii::$app->user->can('sysadmin'),
                'items' => [
                    ['label' => 'User Data', 'url' => ['/access-lists']],
                    ['label' => 'Auth Assignment', 'url' => ['/auth-assignment']],
                ]
            ],
            ['label' => 'Logging', 'icon' => 'gear', 'url' => ['reward-log/index'], 'visible' => Yii::$app->user->can('sysadmin')],

        ],
        'visible' => (Yii::$app->user->can('sysadmin') || $isAdmin),
    ],



    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
];

?>
<aside class="main-sidebar">

    <section class="sidebar">
        <style>
            infoleft {
                margin-left: 10px;
            }

            .infoleft {
                margin-left: 10px;
                margin-top: 10px;
            }

            body.sidebar-collapse .main-sidebar .user-panel .hide-mini,
            body.sidebar-collapse .main-sidebar .user-panel .user-name-info-hide-mini {
                display: none
            }

            body.sidebar-collapse .main-sidebar .user-panel .profile-user-img {
                width: 40px
            }

        </style>

        <!--Left-->
        <?php
        if ($isAdmin && $isAdminProjection && !Yii::$app->user->isGuest) {
            ?>
            <div class="user-panel">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                           <!--<img class="profile-user-img img-fluid img-circle center"
                          src="<?/*= Yii::getAlias('@web') */?> . /img/avatar-user.png" >-->

                     <!--<img class="profile-user-img img-fluid img-circle"
                                 src="<?/*= Helpers::PICTURE_URL . $people->person_id */?>">-->

                        </div>
                        <br>
                        <div class="text-center user-name-info-hide-mini">
                            <p style="color:#FFFFFF; font-size:14px;"><span
                                        style="font-weight:bold">
                                <?php
                                if (!empty(Yii::$app->user->identity->nik) && !empty(Yii::$app->user->identity->employee)) {
                                    echo substr(Yii::$app->user->identity->employee->nama, 0, 20);
                                } else {
                                    echo substr(Yii::$app->user->identity->username, 0, 17);
                                }
                                ?>
                            </span><br/>
                                <?= substr($people->title, 0, 25); ?><br/>
                            </p>
                            <p style="color:#FFFFFF; font-size:12px;">
                                <?php echo '[' . Yii::$app->user->identity->employee->nik . ']'; ?>

                                &nbsp;&nbsp;
                                <?= Html::a(
                                    '<i class="glyphicon glyphicon-log-out text-danger"></i> Sign Out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'title' => 'Sign Out Application', 'style' => 'font-size:13px;']
                                ) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <?php
                /*            if (!$isAdmin && !$isAdminProjection && !Yii::$app->user->isGuest) {
                                */ ?><!--
                <div class="card card-primary hide-mini">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            <? /*= $people->organization */ ?>
                        </p>
                        <p class="text-muted">
                            <? /*= $people->location */ ?>
                        </p>
                        <p class="text-muted">
                            <? /*= $people->bi */ ?> / <? /*= $people->bp */ ?>
                        </p>
                        <p class="text-muted">
                            <? /*= $people->employee_category */ ?>
                        </p>
                    </div>
                </div>
                --><?php
                /*            }
                            */ ?>
            </div>
            <!--/Left-->
        <?php } ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => $menus
            ]
        ) ?>

    </section>

</aside>
