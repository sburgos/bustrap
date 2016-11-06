<?php
use common\yii\helpers\StringHelper;
use common\yii\helpers\CompileHelper;

$params = require(__DIR__ . '/params.php');

Yii::setAlias('common', dirname(dirname(__DIR__)) . "/common");
Yii::setAlias('modules', dirname(__DIR__) . "/modules");
Yii::setAlias('orm', dirname(__DIR__) . "/orm");
Yii::setAlias('crud', dirname(__DIR__) . "/crud");

$lessFileCompilation = false;

$config = [
    'id' => 'usmp',
    'basePath' => dirname(__DIR__),
	'vendorPath' => dirname(dirname(__DIR__)) . "/vendor",
	'runtimePath' => dirname(dirname(__DIR__)) . "/runtime",
	'defaultRoute' => 'admin/index',
	'layoutPath' => '@app/layouts',
	'layout' => 'layout',
    'bootstrap' => ['log'],
    'on beforeAction' => function($event){

        // Make sure to not index ever
        \Yii::$app->getResponse()->getHeaders()->add('X-Robots-Tag', 'none');

        $publicRoutes = [
            'admin/index/maintenance' => true,
            'admin/index/login' => true,
            'admin/index/error' => true,
        ];

        // Deny if is not a public route
        if (!array_key_exists($event->action->uniqueId, $publicRoutes))
        {
            if(!StringHelper::startsWith($event->action->uniqueId, 'pay')) {
                if (\Yii::$app->user->isGuest) {
                    \Yii::$app->user->loginRequired();
                    \Yii::$app->end();
                }
            }
        }
    },
    'components' => [
        'assetManager' => [
			'class' => 'common\yii\web\AssetManager',
			'basePath' => '@webroot/inc',
			'baseUrl' => '@web/inc',
			'bundles' => [
				'yii\bootstrap\BootstrapAsset' => [
					'class' => 'common\assets\BootstrapAsset',
				],
				'yii\bootstrap\BootstrapPluginAsset' => [
					'class' => 'common\assets\BootstrapPluginAsset',
				],
			],
            /*'forceCopy' => $lessFileCompilation, // On dev always copy again
            'beforeCopy' => !$lessFileCompilation ? null : function($a, $b) {

                $lessVariables = [
                    'brand-primary' => '#e98d05', // orange
                    'body-bg' => '#fefefe',
                    'font-size-base' => '12px',

                    'border-radius-base' => '0px',
                    'border-radius-large' => '0px',
                    'border-radius-small' => '0px',

                    'input-border-focus' => '#e98d05', // orange

                    'grid-columns' => '12',
                    'grid-gutter-width' => '20px',

                    'breadcrumb-bg' => 'transparent',
                    'breadcrumb-color' => '#888888',
                    'breadcrumb-active-color' => '#333333',
                ];

                if (StringHelper::endsWith($a, 'bootstrap/dist/css'))
                {
                    $basePath = StringHelper::dirname(StringHelper::dirname($a));
                    CompileHelper::lessFile(
                        "{$basePath}/less/bootstrap.less",
                        "{$basePath}/dist/css/bootstrap.custom.min.css",
                        $lessVariables,
                        "/usr/local/bin/node /usr/local/bin/lessc"
                    );
                }
                else if (StringHelper::endsWith($a, 'app/assets/dist/css'))
                {
                    $basePath = StringHelper::dirname(StringHelper::dirname($a));
                    CompileHelper::lessFile(
                        "{$basePath}/less/main.less",
                        "{$basePath}/dist/css/main.min.css",
                        $lessVariables,
                        "/usr/local/bin/node /usr/local/bin/lessc"
                    );
                }
                return true;
            },*/
        ],
        'user' => [
            'class' => 'modules\admin\models\UserAdminWeb',
            'identityClass' => 'modules\admin\models\UserAdminIdentity',
            'enableAutoLogin' => true,
            'loginUrl' => ['/admin/index/login'],
            'identityCookie' => [ 'name' => 'usmpcookie' ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '123456',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        
    ],
    'params' => $params,
	'modules' => [
		'admin' => [
			'class' => 'modules\admin\Module',
			'title' => 'Admin',
			'modules' => [
				'crud' => [
					'class' => 'crud\admin\Module',
					'controllerNamespace' => 'crud\admin\controllers',
				],
			],
		],
        'bus' => [
            'class' => 'modules\bus\Module',
            'title' => 'Bus',
            'modules' => [
                'crud' => [
                    'class' => 'crud\bus\Module',
                    'controllerNamespace' => 'crud\bus\controllers',
                ],
            ],
        ],
        'routes' => [
            'class' => 'modules\routes\Module',
            'title' => 'Routes',
            'modules' => [],
        ],
        'test' => [
            'class' => 'modules\test\Module',
            'title' => 'Test',
            'modules' => [],
        ],
		'app'   => [
			'class' => 'modules\app\Module',
			'title' => 'App',
		]
	],
];

if (false) {
    // configuration adjustments for 'dev' environment
 $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
			'generators' => [
				'model' => [
					'class' => 'common\yii\gii\generators\model\Generator'
				],
				'crud' => [
					'class' => 'common\yii\gii\generators\crud\Generator'
				]
			]
    ];
}

return $config;
