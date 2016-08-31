<?php
namespace common\yii\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\DataColumn as YiiDataColumn;

class BooleanColumn extends YiiDataColumn
{
	public $format = 'boolean';
	
	public $width = 10;
	
	public $headerOptions = [
		'style' => '',
		'class' => 'image-column-header',
	];
	
	public $contentOptions = [
		'style' => '',
		'class' => 'image-column-cell',
	];
	
	public function init()
	{
		parent::init();
		
		$style = "width:{$this->width}px;text-align:center;";
		$this->headerOptions['style'] = $style . $this->headerOptions['style'];
		$this->contentOptions['style'] = $style . $this->contentOptions['style'];
	}
	
	protected function renderDataCellContent($model, $key, $index)
	{
		if ($this->content === null) {
			$bool = $this->getDataCellValue($model, $key, $index);
			if ($bool === null)
				return "-";
			if ($bool)
				return "<span class='fa fa-check text-success'></span>";
			else
				return "<span class='fa fa-close text-danger'></span>";
		} else {
			return parent::renderDataCellContent($model, $key, $index);
		}
	}
}