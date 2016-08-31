<?php
namespace common\yii\behaviors;

use yii\behaviors\BlameableBehavior as YiiBlameableBehavior;

class BlameableBehavior extends YiiBlameableBehavior
{
	/**
	 * Evaluates the value of the user.
	 * The return result of this method will be assigned to the current attribute(s).
	 * @param Event $event
	 * @return mixed the value of the user.
	 */
	protected function getValue($event)
	{
		$val = parent::getValue($event);
		if (empty($val))
			return "????";
		return $val;
	}
}