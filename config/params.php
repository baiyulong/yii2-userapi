<?php
return [
	'api' => require(__DIR__ . '/api.php'),
	'apiDebug' => true,
	'requestAuth' => [
		'header' => [
			'AUTHUSER' => 'dratio',
			'AUTHPW'   => 'gWrdx4T7tV3G',
		],
	],
	'notNeedLoginAction' => [
		'auth/login',
	],
];