<?php

namespace backend\assets;

use yii\bootstrap4\BootstrapPluginAsset;
use yii\jui\JuiAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets';
    public $css = [
        [
            'href' => '/img/favicon/favicon.ico',
            'rel' => 'icon',
            'sizes' => '32x32',
        ],
        'css/bootstrap.min.css',
        'libs/flatpickr/flatpickr.min.css',
        'libs/jquery-toast/jquery.toast.min.css',
        'css/icons.min.css',
        'css/app.css',
        'css/custom.css',
    ];
    public $js = [
        'libs/jquery-toast/jquery.toast.min.js',
        'js/jquery.slimscroll.js',
        'js/waves.js',
//        'js/app.min.js',
        'js/custom.js',
    ];
    public $depends = [
        YiiAsset::class,
        JuiAsset::class,
        BootstrapPluginAsset::class
    ];
}
