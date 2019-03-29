<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

// =========== global
$userRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());
$isAdmin = false;
$isAdminProjection = false;
$theRole = '';
foreach ($userRoles as $userRole) {
    if ('reward_admin' == $userRole->name) {
        $isAdmin = true;
//        break;
    }
    if ('reward_projection' == $userRole->name) {
        $isAdminProjection = true;
//        break;
    }
    if ('guest' != $userRole->name) {
        $theRole = $userRole->name;
    }
}
Yii::$app->session->set('isAdmin', $isAdmin);
Yii::$app->session->set('isAdminProjection', $isAdminProjection);
$isGuest = Yii::$app->user->isGuest;
Yii::$app->session->set('isGuest', $isGuest);

if (!$isGuest) {
    // get person_id
    $theEmployee = Yii::$app->user->identity->employee;
    Yii::$app->session->set('loggedInName', '[' . $theEmployee->nik . '] ' . $theEmployee->nama);
    Yii::$app->session->set('loggedInJob', $theEmployee->title);
}

$globalCss = <<< CSS
.label {
    font-size: 85%;
}
.esk-status {
    font-size: 18px;
    border-radius: 5px;
    padding: 5px;
}
CSS;

$this->registerCss($globalCss);

// ============ end global


//$isAdmin = Yii::$app->user->identity->employee->isAdmin;

//$isAdminProjection = Yii::$app->user->identity->employee->isAdminProjection;


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('reward\assets\AppAsset')) {
        reward\assets\AppAsset::register($this);
    } else {
        reward\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>


    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title>
            <?= Html::encode($this->title) ?>
        </title>
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>

        <?php $this->head() ?>
    </head>

    <body class="hold-transition skin-red sidebar-collapse sidebar-mini">
    <!-- BEGIN PRELOADER -->
    <div class="se-pre-con">
        <img alt="" class="img-loader">
    </div>
    <!-- END PRELOADER -->

    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>


        <!-- sidebar check -->
        <div id="show-sidebar">
            <?= $this->render(
                'left.php',
                ['directoryAsset' => $directoryAsset]
            )
            ?>
        </div>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>


    <?php $this->endBody() ?>
    </body>

    <!-- CSS SCRIPT -->
    <?php
    $script = <<< CSS
        
CSS;

    $this->registerCss($script);
    ?>

    <!-- JS SCRIPT -->
    <?php
    $script = "
        $(document).ready(function(){
            var flag_error = $('#flag-error').val();
            if (flag_error == null){
               $('#show-sidebar').show(100);
               $('#body-id').removeClass('layout-top-nav');
               $('.sidebar-toggle').show(100);
            }else{
                $('#show-sidebar').hide(100);
                $('#body-id').addClass('layout-top-nav'); 
                $('.sidebar-toggle').hide(100);
            }
        })";
    $this->registerJs($script);
    ?>

    </html>
    <?php $this->endPage() ?>
<?php } ?>

