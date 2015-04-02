<?php

namespace uapi\userapi\controllers;

use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
	public function actionSignup()
	{
		print_r(Yii::$app->getParams());exit;
		echo 'This is signup action';
	}

	public function actionLogin()
	{
		echo 'This is login action';
	}

	public function actionLogout()
	{
		echo 'This is logout action';
	}
}