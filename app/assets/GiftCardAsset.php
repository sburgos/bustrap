<?php
namespace app\assets;

use yii\web\AssetBundle;

class GiftCardAsset extends AssetBundle
{
	public $sourcePath = '@app/assets/dist';

	public $css = [
		
	];

	public $js = [
		'js/gift-card.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
		'common\assets\FontAwesomeAsset',
	];
}
