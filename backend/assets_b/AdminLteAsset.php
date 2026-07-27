<?php

namespace backend\assets_b;

use yii\base\Exception;
use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/adminlte/dist';
    public $css = [
        'plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css',
        'css/style.css',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css'
    ];
    public $js = [
        'plugins/fastclick/fastclick.min.js',
        'js/app.min.js',
        'plugins/sparkline/jquery.sparkline.min.js',
        'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'plugins/chartjs/Chart.min.js',
        'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',
        'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'
    ];
    public $depends = [
        'backend\assets_b\FontawesomeAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'kartik\editors\assets\SummernoteAsset',
    ];

    public $skin = '_all-skins';

    public function init()
    {
        if ($this->skin) {
            if (('_all-skins' !== $this->skin) && (strpos($this->skin, 'skin-') !== 0)) {
                throw new Exception('Invalid skin specified');
            }

            $this->css[] = sprintf('css/skins/%s.min.css', $this->skin);
        }

        parent::init();
    }
}