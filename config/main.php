<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-reward',
    'name' => 'Reward Management',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'reward\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-esk',
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
        'user' => [
            'identityClass' => 'reward\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-reward', 'httpOnly' => true],
        ],
//         'urlManager' => [
//             'enablePrettyUrl' => true,
//             'showScriptName' => false,
//             'rules' => [
//             ],
//         ],
        
        // 'formatter' => [
            // 'dateFormat' => 'dd.MM.yyyy',
            // 'decimalSeparator' => ',',
            // 'thousandSeparator' => '.',
            // 'currencyCode' => 'EUR',
        // ],
    ],
    'params' => $params,
];
