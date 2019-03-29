<?php

namespace reward\assets;

use yii\web\AssetBundle;

/**
 * Main personalinfo application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/custom-style.css',
        'css/loader.css',
        'wizard_form/css/style.css',
    ];
    public $js = [
//        'wizard_form/jquery/jquery.min.js',
        'wizard_form/jquery-validation/dist/jquery.validate.min.js',
        'wizard_form/jquery-steps/jquery.steps.min.js',
        'wizard_form/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
