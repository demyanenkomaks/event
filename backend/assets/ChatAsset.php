<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class ChatAsset extends AssetBundle
{
    public $sourcePath = '@backend/assets';
    public $css = [];
    public $js = [
        'js/chat.js'
    ];
    public $depends = [
        \backend\assets\AppAsset::class,
    ];
}
