<?php

namespace uapi\userapi;

use Yii;

class Module extends \yii\base\Module
{
    public $defaultRoute = 'auth';

    public $mainLayout = '@uapi/userapi/views/layouts/main.php';

    public function init()
    {
        parent::init();
    }
}
