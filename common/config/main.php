<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.10.254;dbname=automateweb',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ],

        'weChatDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.10.254;dbname=WechatAutomate',
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_frontendUser',
            ],
        ],*/
        /*'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // ...
            ],
        ],*/
    ],
    'language' => 'zh-CN',
];
