<?php
return [
	'api' => require(__DIR__ . '/api.php'),
	'apiDebug' => true,
	'requestAuth' => [
		'header' => [
			'AUTHUSER' => 'test',
			'AUTHPW'   => 'test',
		],
	],
	'notNeedLoginAction' => [
		'auth/login',
	],
];