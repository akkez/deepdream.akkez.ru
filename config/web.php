<?php

$params = require(__DIR__ . '/params.php');
$mail = require(__DIR__ . '/mail.php');

$config = [
	'id'         => 'dream',
	'name'       => 'DeepDream',
	'basePath'   => dirname(__DIR__),
	'bootstrap'  => ['log'],
	'components' => [
		'request'      => [
			'cookieValidationKey' => 'e7Xtz2s_h1qZfmfCnBfyaig6ntYN7X9M',
			'baseUrl'             => '',
		],
		'urlManager'   => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
			'rules'           => [
				''         => 'site/index',
				'<action>' => 'site/<action>',
				'upload/<key:[0-9a-f]{32}>' => 'site/upload',
				'gallery/<algorithmId:\d+>' => 'gallery/index',
				'<controller>/<id:\d+>' => '<controller>/view',
			],
		],
		'cache'        => [
			'class' => 'yii\caching\FileCache',
		],
		'user'         => [
			'identityClass'   => 'app\models\User',
			'enableAutoLogin' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'mail' => [
			'class' => 'yii\swiftmailer\Mailer',
			'transport' => $mail,
		],
		'log'          => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets'    => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'db'           => require(__DIR__ . '/db.php'),
	],
	'params'     => $params,
];

if (YII_ENV_DEV)
{
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][]      = 'debug';
	$config['modules']['debug'] = 'yii\debug\Module';

	$config['bootstrap'][]    = 'gii';
	$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
