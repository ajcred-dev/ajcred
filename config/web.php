<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'AJCRED',
    'timeZone' => 'America/Sao_Paulo',
    'language' => 'PT-BR',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'gridview' => [ 
            'class' => '\kartik\grid\Module' 
        ],
        'acl' => [
            'class' => 'mdm\admin\Module',
            'controllerMap' => [ 
                    'assignment' => [ 
                            'class' => 'mdm\admin\controllers\AssignmentController',
                            'userClassName' => 'app\models\Usuario',
                            'idField' => 'id',
                            'usernameField' => 'email',
                            'searchClass' => 'app\models\UsuarioSearch'
                    ] 
            ],
            'menus' => [ 
                    'user' => false,
                    //'menu' => false,
                    'rule' => false,
                    'permission' => false 
            ],
            'layout' => 'left-menu',
            'mainLayout' => '@app/views/layouts/main.php' 
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mUAw6KZg2QEx8IKYLRvqjf0sMK5JgyVx',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [ 
                'identityClass' => 'app\models\Usuario',
                'enableAutoLogin' => true,
                'identityCookie' => [
                        'name' => 'econtractingIdentityCookie',
                ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [ 
            'class' => 'yii\rbac\DbManager' 
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            //'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => false,
            //'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
				'class' => 'Swift_SmtpTransport',
                //'dsn' => 'smtp.titan.email',
                'scheme' => 'smtp',
				'host' => 'smtp.titan.email',
				'username' => 'informativo@ajcred.com',
				'password' => 'Mkt2010@',
				'port' => '465',
				'encryption' => 'tls',
			],
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
        'db' => $db,
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
    
    /*
    'as access' =>[
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
                'site/login',
                'site/error',
                'site/novo-usuario',
                'site/logout',
                'site/esqueci-senha',
                'usuario/pessoa/save-cache-cv',
                'usuario/pessoa/get-cv-lote',
                'usuario/pessoa/get-cv-html'
        ]
    ]
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
