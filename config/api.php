<?php

/**
 * 远程接口配置
 */
$authUrl = 'http://test.uc-api.dratio.com/';
return [
    'auth::login' => [
        'url' => $authUrl . 'auth/login',
    ],
    'auth::getInfo' => [
    	'url' => $authUrl . 'user/getInfo',
    ],
    'auth::add' => [
        'url' => $authUrl . 'user/add',
        'method' => 'post',
    ],
    'auth::updatePwd' => [
        'url' => $authUrl . 'auth/editPassword',
        'method' => 'post',
    ],
    'auth::addRecord' => [
    	'url' => $authUrl . 'record/add',
        'method' => 'post',
    ],
    'mapping::add' => [
        'url' => $authUrl . 'mapping/add',
        'method' => 'post',
    ],
    'mapping::getInfo' => [
        'url' => $authUrl . 'mapping/getInfo',
        'method' => 'post',
    ],
    'mapping::edit' => [
        'url' => $authUrl . 'mapping/edit',
        'method' => 'post',
    ],
    'mapping::delete' => [
        'url' => $authUrl . 'mapping/delete',
        'method' => 'post',
    ]
];
