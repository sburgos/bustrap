<?php
namespace common\yii\helpers;

use Yii;
use yii\helpers\BaseHtml;
use yii\helpers\Inflector;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Url;

class Html extends BaseHtml
{
	/**
	 * Creates a link only if the user has access to that url
	 * 
	 * @param string $text
	 * @param string|array $url
	 * @param array $options
	 * @return string
	 */
	public static function aACL($text, $url, $options = [])
	{
		if (Yii::$app->user->isGuest)
			return "";
		if (!Yii::$app->user->can(Url::toRoute($url)))
			return "";
		return parent::a($text, $url, $options);
	}
}