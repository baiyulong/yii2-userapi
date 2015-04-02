<?php

namespace yii\userapi;

use Yii;
use yii\base\Module;

class Module extends Module
{
    public $defaultRoute = 'auth';

    public $mainLayout = '@yii/userapi/views/layouts/main.php';

    public function init()
    {
        parent::init();
    }
}
