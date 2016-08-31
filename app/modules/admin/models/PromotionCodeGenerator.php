<?php
namespace modules\admin\models;

use orm\admin\PromotionCode;

class PromotionCodeGenerator extends PromotionCode
{
	public $prefix;
	
	public $length = 16;
	
	public $quantity = 1;
	
	public function rules()
	{
		return array_merge(parent::rules(), [
			[['length', 'quantity'], 'required'],
			[['length', 'quantity'], 'number', 'integerOnly' => true],
			[['quantity'], 'number', 'integerOnly' => true, 'min' => 1, 'max' => 10000],
			['prefix', 'string', 'max' => 10],
		]);
	}
}