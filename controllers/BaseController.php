<?php
namespace uapi\userapi\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class BaseController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 输出异常信息
     * @author baiyulong<baiyulong@dratio.com>
     * @param  object $e 异常对象
     * @return string    有格式的错误报告
     */
    public function _showException($e)
    {
        echo get_class($e) . ": " . $e->getMessage() . "<br>";
        echo "File=" . $e->getFile() . "<br>";
        echo "Line=" . $e->getLine() . "<br>";
        echo str_replace('#', '<br>#', $e->getTraceAsString());
    }
}