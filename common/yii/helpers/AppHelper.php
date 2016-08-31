<?php
namespace common\yii\helpers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Inflector;

class AppHelper
{
	/**
	 * Return the root module being requested. If inside a submodule it
	 * will go up the hierarchy till it reaches the root module before
	 * the application
	 * 
	 * @return \yii\base\Module
	 */
	public static function getRootModule()
	{
		if (!Yii::$app->controller || !Yii::$app->controller->module)
			return Yii::$app->module;
		$module = Yii::$app->controller->module;
		while ($module && $module->module && !($module->module instanceof \yii\web\Application))
			$module = $module->module;
		return $module;
	}
}