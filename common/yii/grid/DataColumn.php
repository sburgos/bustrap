<?php
namespace common\yii\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\DataColumn as YiiDataColumn;
use yii\helpers\Url;
use common\yii\helpers\StringHelper;
use yii\helpers\Inflector;

class DataColumn extends YiiDataColumn
{
	public $isPrimaryKey = false;

	public $controller = false;

	/**
	 * @inheritdoc
	 */
	protected function renderDataCellContent($model, $key, $index)
	{
		$res = parent::renderDataCellContent($model, $key, $index);
		
		if ($this->isPrimaryKey)
		{
			$params = is_array($key) ? $key : ['id' => (string) $key];
            $params[0] = $this->controller ? $this->controller . '/view' : 'view';
            $url = Url::toRoute($params);
            
            if (!\Yii::$app->user->isGuest)
            {
	            if (\Yii::$app->user->can($url))
					$res = "<a href='{$url}'><strong>{$res}</strong></a>";
            }
		}
		return $res;
	}
}