<?php
namespace common\yii\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\DataColumn as YiiDataColumn;

class IntegerColumn extends YiiDataColumn
{
	public $format = 'integer';
	
	public $width = 10;
	
	public $headerOptions = [
		'style' => '',
		'class' => 'integer-column-header',
	];
	
	public $contentOptions = [
		'style' => '',
		'class' => 'integer-column-cell',
	];
	
	public function init()
	{
		parent::init();
		
		$style = "width:{$this->width}px;text-align:center;";
		$this->headerOptions['style'] = $style . $this->headerOptions['style'];
		$this->contentOptions['style'] = $style . $this->contentOptions['style'];
	}
}