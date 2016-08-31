<?php
namespace app\layouts\widgets;

use Yii;
use common\yii\helpers\Html;
use common\yii\helpers\ArrayHelper;

class Nav extends \yii\bootstrap\Nav
{
	/**
	 * Adds the ability to detect visibility based on the user permits
	 * 
	 * (non-PHPdoc)
	 * @see \yii\bootstrap\Nav::renderItems()
	 */
	public function renderItems()
	{
		$items = [];
		foreach ($this->items as $i => $item) {
			if (!isset($item['visible']) && is_array($item))
				$item['visible'] = $this->getVisibility($item);
			if (isset($item['visible']) && !$item['visible']) {
				unset($items[$i]);
				continue;
			}
			if (isset($item['items']) && is_array($item['items'])) {
				$visibles = 0;
				foreach ($item['items'] as &$childItem)
				{
					if (!isset($childItem['visible']) && is_array($childItem))
						$childItem['visible'] = $this->getVisibility($childItem);
					if (is_array($childItem) && $childItem['visible'])
						$visibles++;
				}
				if ($visibles == 0) continue;
			}
			$items[] = $this->renderItem($item);
		}
	
		return Html::tag('ul', implode("\n", $items), $this->options);
	}
	/**
	 * Determine the visibility of an item in the nav to be rendered
	 * 
	 * @param [] $item
	 * @return boolean
	 */
	public function getVisibility($item)
	{
		if (is_string($item)) {
			return true;
		}
		if (isset($item['items']))
		{
			$count = count($item['items']);
			
		}
		if (!isset($item['url'])) {
			return true;
		}
		if (Yii::$app->user->isGuest)
			return true;
		return Yii::$app->user->can($item['url']);
	}
}