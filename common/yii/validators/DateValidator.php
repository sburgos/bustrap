<?php
namespace common\yii\validators;

use yii\validators\DateValidator as YiiDateValidator;

class DateValidator extends YiiDateValidator
{
	public $timeZoneAttribute = null;
	
	/**
	 * Determine the timezone from the model itself or
	 * from one of it's relations
	 * 
	 * (non-PHPdoc)
	 * @see \yii\validators\DateValidator::validateAttribute()
	 */
	public function validateAttribute($model, $attribute)
	{
		if ($this->timeZoneAttribute !== null)
		{
			if (is_string($this->timeZoneAttribute))
				$this->timeZoneAttribute = [$this->timeZoneAttribute];
			if (is_array($this->timeZoneAttribute))
			{
				$attrs = $this->timeZoneAttribute;
				$timezone = $model;
				do {
					$key = array_shift($attrs);
					$timezone = $timezone->{$key};
					if ($timezone instanceof \yii\db\ActiveQuery)
						$timezone = $timezone->one();
				}
				while (count($attrs) > 0);
				$this->timeZone = $timezone;
			}
		}
		parent::validateAttribute($model, $attribute);
	}
	
	/**
	 * Return the date as a string instead of as a timestamp
	 * when the format contains php: on front
	 * 
	 * (non-PHPdoc)
	 * @see \yii\validators\DateValidator::parseDateValue()
	 */
	protected function parseDateValue($value)
	{
		$timestamp = parent::parseDateValue($value);
		if ($timestamp === false)
			return $timestamp;
		$format = $this->format;
		if (strncmp($this->format, 'php:', 4) === 0) {
			$format = substr($format, 4);
			return date($format, $timestamp);
		}
		return $timestamp;
	}
}