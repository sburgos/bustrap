<?php
namespace common\yii\grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn as YiiActionColumn;
use yii\helpers\Url;
use common\yii\helpers\StringHelper;
use yii\helpers\Inflector;

class ActionColumn extends YiiActionColumn
{
	public $template = '{update}{delete}';

	public $width = 30;
	
	public $deleteUrlParams = [];
	
	public $editUrlParams = [];
	
	public $headerOptions = [
		'class'=>'action-column-header',
		'style' => '',
	];
	
	public $contentOptions = [
		'class' => 'action-column-cell',
		'style' => 'padding:0px;white-space:nowrap;',
	];
	
	public function init()
	{
		parent::init();
		
		$style = "width:{$this->width}px;text-align:center;";
		$this->headerOptions['style'] = $style . $this->headerOptions['style'];
		$this->contentOptions['style'] = $style . $this->contentOptions['style'];
		//$this->template = '<div class="btn-group btn-group-justified">' . $this->template . '</div>';
	}
	
	protected function initDefaultButtons()
	{
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                return Html::a('<span class="fa fa-eye"></span>', $url, [
                    'title' => Yii::t('yii', 'View'),
                    'data-pjax' => '1',
                    'class' => 'btn btn-info',
                ]);
            };
        }
        if (!isset($this->buttons['preview'])) {
            $this->buttons['preview'] = function ($url, $model) {
                $url='http://www.cinepapaya.com/noticia/'.$model->titleSlug.'-'.$model->id;
                return Html::a('<span class="fa fa-eye"></span>', $url, [
                    'title' => Yii::t('crud/cine', 'Preview'),
                    'data-pjax' => '1',
                    'class' => 'btn btn-info',
                    'target' => '_blank',

                ]);
            };
        }
        if (!isset($this->buttons['update'])) {
        	$urlParams = $this->editUrlParams;
            $this->buttons['update'] = function ($url, $model) use ($urlParams) {
            	$urlSep = strpos($url, "?") !== false ? "&" : "?";
            	if (count($urlParams) > 0)
            	{
            		$url .= $urlSep;
            		$parts = [];
            		foreach ($urlParams as $key => $value)
            			$parts[] = "{$key}=" . urlencode($value);
            		$url .= implode("&", $parts);
            	}
            	return Html::a('<span class="fa fa-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
					'class' => 'btn btn-primary',
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
        	$urlParams = $this->deleteUrlParams;
            $this->buttons['delete'] = function ($url, $model) use ($urlParams) {
            	$urlSep = strpos($url, "?") !== false ? "&" : "?";
            	if (count($urlParams) > 0)
            	{
            		$url .= $urlSep;
            		$parts = [];
            		foreach ($urlParams as $key => $value)
            			$parts[] = "{$key}=" . urlencode($value);
            		$url .= implode("&", $parts);
            	}
                return Html::a('<span class="fa fa-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
					'class' => 'btn btn-danger',
                ]);
            };
        }
		parent::initDefaultButtons();
	}
	
	protected function renderDataCellContent($model, $key, $index)
	{
		return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
			$name = $matches[1];
			if (isset($this->buttons[$name])) {
				$url = $this->createUrl($name, $model, $key, $index);

				if (\Yii::$app->user->can($url))
					return call_user_func($this->buttons[$name], $url, $model, $key);
				else
					return '';
			} else {
				return '';
			}
		}, $this->template);
	}
}