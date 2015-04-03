<?php

namespace uapi\userapi;

use Yii;
use yii\web\AssetBundle;

class UapiAsset extends AssetBundle
{
    public $sourcePath = '@uapi/userapi/assets';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
