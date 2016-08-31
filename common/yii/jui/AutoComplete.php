<?php
namespace common\yii\jui;

use yii\helpers\Html;
use yii\web\JsExpression;

class AutoComplete extends \yii\jui\AutoComplete
{
	public $crudUrl = null;
	public $crudUrlGet = '/ajax-get?id=';
	public $crudUrlList = '/ajax-list';
	
	protected function registerWidget($name, $id = null)
	{
		if ($id === null) 
		{
			$this->crudUrl = rtrim($this->crudUrl, "/");
			if ($this->hasModel())
			{
				$fieldId = Html::getInputId($this->model, $this->attribute);
				$id = $this->getAutoCompleteId();
				$this->clientOptions['select'] = new JsExpression("function(event, ui){" .
					"$('#{$fieldId}').val(ui.item.value);".
					"$('#{$id}').val(ui.item.label);".
					"$('#{$id}value').html(ui.item.value);".
					"event.preventDefault();".
				"}");
				$this->clientOptions['source'] = $this->crudUrl . $this->crudUrlList;
				
				// Register the initial load of the autocomplete label
				$url = $this->crudUrl . $this->crudUrlGet . Html::getAttributeValue($this->model, $this->attribute);
				$jsInit = "$.ajax({dataType:'json',url:'{$url}',success:function(res){".
					"if (res && res.label) $('#{$id}').val(res.label);".	
				"}})";
				$value = Html::getAttributeValue($this->model, $this->attribute);
				if (!empty($value))
					$this->getView()->registerJs($jsInit);
			}
		}
		parent::registerWidget($name, $id);
	}
	
	public function getAutoCompleteId()
	{
		if ($this->hasModel())
			return Html::getInputId($this->model, $this->attribute) . "-auto-complete";
		else
			"";
	}
	
	/**
     * Renders the AutoComplete widget.
     * @return string the rendering result.
     */
    public function renderWidget()
    {
        if ($this->hasModel()) 
        {
        	if (empty($this->crudUrl))
        		throw new \Exception("Incorrect config 'crudUrl' is required");
        	
        	$fieldId = Html::getInputId($this->model, $this->attribute);
        	$inputAutoId = $this->getAutoCompleteId();
        	$defaultInput = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        	$value = Html::getAttributeValue($this->model, $this->attribute);
        	$autoCompleteInput = Html::textInput($inputAutoId, '', [
        		'id' => $inputAutoId,
        		'class' => 'form-control',
        	]);
        	$clearButtonClick = "$('#{$fieldId}').val('');$('#{$inputAutoId}value').html('');$('#{$inputAutoId}').val('');";
        	$clearButton = "<button type='button' class='btn btn-default' tabindex='-1' onclick=\"{$clearButtonClick}\"><span class='fa fa-close'></span></button>";
        	
        	return
        		$defaultInput .
        		"<div class='input-group'>". 
        			"<span class='input-group-addon' id='{$inputAutoId}value'>{$value}</span>" . 
        			$autoCompleteInput . 
        			"<span class='input-group-btn'>{$clearButton}</span>".
        		"</div>";
        } 
        else {
            return Html::textInput($this->name, $this->value, $this->options);
        }
    }
}