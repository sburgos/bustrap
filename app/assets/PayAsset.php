<?php

namespace app\assets;

use yii\web\AssetBundle;

class PayAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/dist';
    
    public $css = [
        'css/pay.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    	'common\assets\FontAwesomeAsset',
    ];
}
