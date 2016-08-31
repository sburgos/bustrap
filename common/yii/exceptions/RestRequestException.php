<?php
namespace common\yii\exceptions;

class RestRequestException extends \Exception
{
	protected $_data = null;
	protected $_messageDetail = null;
	
	public function __construct($code, $data)
	{
		$errorMsg = "Internal Error";
		if (!empty($data) && is_array($data))
		{
			if (isset($data['name']))
				$errorMsg = $data['name'];
			if (isset($data['message']))
				$this->_messageDetail = $data['message'];
		}
		parent::__construct($errorMsg, $code);
		$this->_data = $data;
	}
	
	public function getData()
	{
		return $this->_data;
	}
	
	public function getMessageDetail()
	{
		return $this->_messageDetail;
	}
}