<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'ildis',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'backend\modules\admin\Module',
            'viewPath' => '@backend/modules/admin/views',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
    ],

    'aliases' => [
        '@adminlte/widgets' => '@vendor/adminlte/yii2-widgets'
    ],

    'components' => [

        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV3' => Yii::$app->params['recaptcha.siteKey'],
            'secretV3' => Yii::$app->params['recaptcha.secretKey'],
        ],
        //  'formatter' => [
        //   'class' => 'yii\i18n\Formatter',
        //   'nullDisplay' => '',
        // ],

        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            //'dateFormat' =>'MM/dd/yyyy',
            'nullDisplay' => '',
            'dateFormat' => 'php:Y-m-d',
            'datetimeFormat' => 'php:d-M-Y H:i:s',
            'timeFormat' => 'php:H:i:s',

        ],
        
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 300,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'ildis-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'suffix' => '.html',
            'rules' => [

                // 'article/<id:\d+>/<slug>' => 'article/view',
                //'<controller:\w+>/<id:\d+>/' => '<controller>/view',

                //   '<controller:\w+>/<id:\d+>/<slug>' => '<controller>/view',
                //   '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                //   '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ],
        ],
    ],

    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
              //'site/*', 
              
        ],
    ],
    'params' => $params,
];
