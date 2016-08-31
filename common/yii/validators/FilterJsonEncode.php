<?php
namespace common\yii\validators;

use Yii;
use yii\validators\Validator;

class FilterJsonEncode extends Validator
{
	/**
	 * @var number options to pass to the json_encode function
	 */
	public $jsonOptions = JSON_FORCE_OBJECT;
	
	/**
     * @var boolean whether the filter should be skipped if an array input is given.
     * If false and an array input is given, the filter will not be applied.
     */
    public $skipOnArray = false;
    
    /**
     * @var boolean this property is overwritten to be false so that this validator will
     * be applied when the value being validated is empty.
     */
    public $skipOnEmpty = false;

    public function init()
    {
    	parent::init();
    	if ($this->message === null) {
    		$this->message = '{attribute} must be a valid json encoded string.';
    	}
    }
    
    /**
     * Convert anything that is not a string to json and perform
     * the parent validation
     * 
     * (non-PHPdoc)
     * @see \yii\validators\FilterValidator::validateAttribute()
     */
	public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (empty($value))
        	return;
        if (!is_string($value)) {
        	$model->$attribute = @json_encode($value, $this->jsonOptions);
        }
        return parent::validateAttribute($model, $attribute);
    }
    
    /**
     * Make sure that we the string is a valid json string
     * 
     * (non-PHPdoc)
     * @see \yii\validators\Validator::validateValue()
     */
    protected function validateValue($value)
    {
    	if ($this->skipOnEmpty && empty($value))
    		return null;
    	if (!is_string($value))
    		return [$this->message, []];
    	$data = @json_decode($value, true);
    	if ($data === null)
    		return [$this->message, []];
    	return null;
    }
}