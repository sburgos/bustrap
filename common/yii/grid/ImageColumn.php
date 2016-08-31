<?php
namespace common\yii\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\DataColumn as YiiDataColumn;

class ImageColumn extends YiiDataColumn
{
	public $format = 'html';
	
	public $width = null;
	public $height = 80;
	
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
		
		$style = "text-align:center;";
		if ($this->width !== null)
			$style .= "width:100px;";
		else
			$style .= "width:140px;";
		$this->headerOptions['style'] = $style . $this->headerOptions['style'];
		$this->contentOptions['style'] = $style . $this->contentOptions['style'];
	}
	
	protected function renderDataCellContent($model, $key, $index)
	{
		if ($this->content === null) {
			$src = $this->getDataCellValue($model, $key, $index);
			if (empty($src))
				return "-";
			if ($this->width !== null)
				$style = "width:{$this->width}px;";
			else 
				$style = "max-width:100%;";
			if ($this->height !== null)
				$style .= "height:{$this->height}px;";
			return Html::img($src, ['style' => $style]);
		} else {
			return parent::renderDataCellContent($model, $key, $index);
		}
	}
}