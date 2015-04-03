UserCenterRemoteApi
===================
用于Dratio的用户中心系统注册、登录相关

安装
----------------------------------------------------------------------------

最好使用composer安装，如果没有composer，请看(http://getcomposer.org/download/).

运行

```
php composer.phar require --prefer-dist baiyulong/yii2-userapi "*"
```

或者

```
"baiyulong/yii2-userapi": "*"
```

使用
----------------------------------------------------------------------------

在你的项目配置文件里添加如下配置

```
'modules' => [
    'uapi' => [
        'class' => 'uapi\userapi\Module',
        'defaultRoute' => 'auth',
        'controllerMap' => [
            'auth' => [
                'class' => 'uapi\userapi\controllers\AuthController',
                'layout' => '@uapi/userapi/views/layouts/main.php',
                'defaultAction' => 'login',
            ],
        ],
    ],
],
'components' => [
    'user' => [
        'identityClass' => 'uapi\userapi\models\User', //处理用户相关登录的model
        'enableAutoLogin' => true,
        'loginUrl' => '/uapi/auth/login', //登录URL地址
    ],
],
```
现在你可以在你的浏览器使用
http://domain/uapi
是不是看到登录界面了？

----------------------------------------------------------------------------

关于组件，安装成功之后在vendor目录下你会找到如下：
```
├── vendor
│   ├── baiyulong  	//这里是组件的全部内容
│   │   └── yii2-userapi
│   │       ├── assets
│   │       │   └── css
│   │       │       └── site.css
│   │       ├── AutoloadExample.php
│   │       ├── composer.json
│   │       ├── config
│   │       │   ├── api.php 	//这里是相关接口配置
│   │       │   └── params.php 	//同上，如需修改请在这里修改
│   │       ├── controllers
│   │       │   ├── AuthController.php
│   │       │   └── BaseController.php
│   │       ├── LICENSE
│   │       ├── models
│   │       │   ├── Auth.php
│   │       │   ├── LoginForm.php
│   │       │   ├── Mapping.php
│   │       │   ├── RemoteModel.php
│   │       │   └── User.php
│   │       ├── Module.php
│   │       ├── README.md
│   │       ├── UapiAsset.php
│   │       └── views
│   │           ├── auth
│   │           │   └── login.php
│   │           └── layouts
│   │               └── main.php
```