<?php

namespace uapi\userapi\controllers;

use Yii;
use uapi\userapi\models;

class AuthController extends BaseController
{
	public function actionSignup()
	{
		echo 'This is signup action';
	}

	public function actionLogin()
	{
		try {
			if (!Yii::$app->user->isGuest) {
				return $this->redirect('site/index');
			}

			$model = new models\LoginForm();
			if ($model->load(Yii::$app->request->post()) && $model->login()) {
				return $this->redirect('/site/index');
			} else {
				return $this->render('login', ['model' => $model]);
			}
		} catch (Exception $e) {
			$this->_showException($e);
		}
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
    	return $this->goHome();
	}
}