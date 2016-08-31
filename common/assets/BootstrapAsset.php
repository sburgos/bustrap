<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BootstrapAsset extends \yii\bootstrap\BootstrapAsset
{
    public $sourcePath = '@common/assets/bootstrap-3.3.1/dist';
    
    public $css = [
        'css/bootstrap.custom.min.css',
    ];
}
