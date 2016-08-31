<?php
namespace app\layouts\widgets;

use Yii;
use common\yii\helpers\Html;
use common\yii\helpers\ArrayHelper;

class Dropdown extends \yii\bootstrap\Dropdown
{
	/**
	 * Renders menu items.
	 * @param array $items the menu items to be rendered
	 * @return string the rendering result.
	 * @throws InvalidConfigException if the label option is not specified in one of the items.
	 */
	protected function renderItems($items)
	{
		$lines = [];
		foreach ($items as $i => $item) {
			if (!isset($item['visible']) && is_array($item))
				$item['visible'] = $this->getVisibility($item);
			if (isset($item['visible']) && !$item['visible']) {
				unset($items[$i]);
				continue;
			}
			if (is_string($item)) {
				$lines[] = $item;
				continue;
			}
			if (!isset($item['label'])) {
				throw new InvalidConfigException("The 'label' option is required.");
			}
			$encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
			$label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
			$options = ArrayHelper::getValue($item, 'options', []);
			$linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
			$linkOptions['tabindex'] = '-1';
			$content = Html::a($label, ArrayHelper::getValue($item, 'url', '#'), $linkOptions);
			if (!empty($item['items'])) {
				unset($this->_containerOptions['id']);
				$content .= $this->renderItems($item['items']);
				Html::addCssClass($options, 'dropdown-submenu');
			}
			$lines[] = Html::tag('li', $content, $options);
		}
	
		return Html::tag('ul', implode("\n", $lines), $this->_containerOptions);
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