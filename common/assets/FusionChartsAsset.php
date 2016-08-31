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
class FusionChartsAsset extends AssetBundle
{
    public $sourcePath = '@common/assets/fusion-charts/js';
    
    public $css = [
        
    ];
    
    public $js = [
    	'fusioncharts.js',
    	'fusioncharts.charts.js',
    	'themes/fusioncharts.theme.fint.js',
    ];
    
    public $depends = [
    ];
}
