<?php

namespace backend\assets_b;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/assets_b';
    public $css = [
        'css/site.css',
        'summernote/dist/summernote.css'
    ];
    public $js = [
        'summernote/dist/summernote.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
