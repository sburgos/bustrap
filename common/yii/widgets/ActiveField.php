<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\yii\widgets;

use Yii;
use common\yii\helpers\Html;
use common\yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;

/**
 * ActiveField represents a form input field within an [[ActiveForm]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var boolean whether to perform validation when the value of the input field is changed.
     * If not set, it will take the value of [[ActiveForm::validateOnChange]].
     */
    public $validateOnChange;
    /**
     * @var boolean whether to perform validation when the input field loses focus.
     * If not set, it will take the value of [[ActiveForm::validateOnBlur]].
     */
    public $validateOnBlur;
    /**
     * @var boolean whether to perform validation while the user is typing in the input field.
     * If not set, it will take the value of [[ActiveForm::validateOnType]].
     * @see validationDelay
     */
    public $validateOnType;
    /**
     * @var integer number of milliseconds that the validation should be delayed when the user types in the field
     * and [[validateOnType]] is set true.
     * If not set, it will take the value of [[ActiveForm::validationDelay]].
     */
    public $validationDelay;
    
    /**
     * Make the checkbox default not enclosed by label
     * 
     * (non-PHPdoc)
     * @see \yii\widgets\ActiveField::checkbox($options, $enclosedByLabel)
     */
    public function checkbox($options = [], $enclosedByLabel = false, $allowNull = false)
    {
    	if ($allowNull)
    		return parent::dropDownList(['' => '', '1' => 'Yes', '0' => 'No'], []);
    	return parent::checkbox($options, $enclosedByLabel);
    }
    
    /**
     * Display a token field input by changing the font family to Consolas
     * @param array $options
     * @return \yii\widgets\static
     */
    public function tokenInput($options = [], $allowNulls = false)
    {
    	if (is_array($options))
    	{ 
    		$font = 'font-size:12px;font-family:Menlo,Monaco,Consolas,"Courier New",monospace';
    		if (isset($options['style']))
    			$options['style'] .= $font;
    		else
    			$options['style'] = $font;
    	}
    	else 
    		$options = ['style' => $font];
    	$value = Html::getAttributeValue($this->model, $this->attribute);
    	if (empty($value) && !$allowNulls)
    		$options['value'] = \Yii::$app->getSecurity()->generateRandomString(ArrayHelper::getValue($options, 'maxlength'));
    	return $this->textInput($options);
    } 
    
    /**
     * Display a date picker
     * @param array $options
     * @return \yii\widgets\static
     */
    public function dateInput($options = [])
    {
    	$dateInput = \yii\jui\DatePicker::widget([
    		'name' => Html::getInputName($this->model, $this->attribute),
    		'value' => Html::getAttributeValue($this->model, $this->attribute),
    		'id' => Html::getInputId($this->model, $this->attribute),
    		'dateFormat' => 'php:Y-m-d',
    		'options' => [
    			'class' => 'form-control',
    		],
    	]);
    	$this->parts['{input}'] = $dateInput;
    	return $this;
    }
    
    /**
     * Display a date and time picker
     * @param array $options
     * @return \yii\widgets\static
     */
    public function dateTimeInput($options = [])
    {
    	if ($this->inputOptions !== ['class' => 'form-control']) {
    		$options = array_merge($this->inputOptions, $options);
    	}
    	$this->adjustLabelFor($options);
    	$value = Html::getAttributeValue($this->model, $this->attribute);
    	$dateValue = "";
    	$hourValue = "";
    	$minuteValue = "";
    	$secondValue = "";
    	if (!empty($value))
    	{
    		$dateValue = substr($value, 0, 10);
    		$hourValue = substr($value, 11, 2);
    		$minuteValue = substr($value, 14, 2);
    		$secondValue = substr($value, 17, 2);
    	}
    	
    	$inputId = Html::getInputId($this->model, $this->attribute);
    	$dateId = $inputId . "-date";
    	$hourId = $inputId . "-hour";
    	$minuteId = $inputId . "-minute";
    	$secondId = $inputId . "-second";
    	
    	$onChange = "$('#{$inputId}').val($('#{$dateId}').val() + ' ' + $('#{$hourId}').val() + ':' + $('#{$minuteId}').val() + ':' + $('#{$secondId}').val())";
    		
    	$hiddenInput = Html::activeHiddenInput($this->model, $this->attribute);
    	
    	$dateInput = \yii\jui\DatePicker::widget([
    		'name' => $dateId,
    		'value' => empty($dateValue) ? null : $dateValue,
    		'id' => $dateId,
    		'dateFormat' => 'php:Y-m-d',
    		'options' => [
    			'class' => 'form-control',
    			'onchange' => "{$onChange}",
    		],
    	]);
    	$hourInput = Html::dropDownList($hourId, $hourValue, 
    		ArrayHelper::buildNumeric(0, 23, 1, "%02d", true, true, ""),
    		[
    			'id' => $hourId,
    			'class' => 'form-control',	
    			'onchange' => "{$onChange}",
    		]
    	);
    	$minuteInput = Html::dropDownList($minuteId, $minuteValue, 
    		ArrayHelper::buildNumeric(0, 59, 1, "%02d", true, true, ""),
    		[
    			'id' => $minuteId,
    			'class' => 'form-control',	
    			'onchange' => "{$onChange}",
    		]
    	);
    	$secondInput = Html::dropDownList($secondId, $secondValue, 
    		ArrayHelper::buildNumeric(0, 59, 1, "%02d", true, true, ""),
    		[
    			'id' => $secondId,
    			'class' => 'form-control',	
    			'onchange' => "{$onChange}",
    		]
    	);
    	
    	$this->parts['{input}'] = 
    		$hiddenInput . 
    		"<div class='row'>" .
    			"<div class='col-xs-6' style='padding-right:0px;'>{$dateInput}</div>" .
    			"<div class='col-xs-2' style='padding:0px;'>{$hourInput}</div>" .
    			"<div class='col-xs-2' style='padding:0px;'>{$minuteInput}</div>" .
    			"<div class='col-xs-2' style='padding-left:0px;'>{$secondInput}</div>" .
    		"</div>";
		return $this;
    }
    


    /**
     * Display a time picker
     * @param array $options
     * @return \yii\widgets\static
     */
    public function timeInput($options = [])
    {
    	if ($this->inputOptions !== ['class' => 'form-control']) {
    		$options = array_merge($this->inputOptions, $options);
    	}
    	$this->adjustLabelFor($options);
    	$value = Html::getAttributeValue($this->model, $this->attribute);
    	$hourValue = "";
    	$minuteValue = "";
    	$secondValue = "";
    	if (!empty($value))
    	{
    		$hourValue = substr($value, 0, 2);
    		$minuteValue = substr($value, 3, 2);
    		$secondValue = substr($value, 6, 2);
    	}
    	 
    	$inputId = Html::getInputId($this->model, $this->attribute);
    	$hourId = $inputId . "-hour";
    	$minuteId = $inputId . "-minute";
    	$secondId = $inputId . "-second";
    	 
    	$onChange = "$('#{$inputId}').val($('#{$hourId}').val() + ':' + $('#{$minuteId}').val() + ':' + $('#{$secondId}').val())";
    
    	$hiddenInput = Html::activeHiddenInput($this->model, $this->attribute);
    	 
    	$hourInput = Html::dropDownList($hourId, $hourValue,
    		ArrayHelper::buildNumeric(0, 23, 1, "%02d", true, true, ""),
    		[
    			'id' => $hourId,
    			'class' => 'form-control',
    			'onchange' => "{$onChange}",
    			]
    	);
    	$minuteInput = Html::dropDownList($minuteId, $minuteValue,
    		ArrayHelper::buildNumeric(0, 59, 1, "%02d", true, true, ""),
    		[
    			'id' => $minuteId,
    			'class' => 'form-control',
    			'onchange' => "{$onChange}",
    			]
    	);
    	$secondInput = Html::dropDownList($secondId, $secondValue,
    		ArrayHelper::buildNumeric(0, 59, 1, "%02d", true, true, ""),
    		[
    			'id' => $secondId,
    			'class' => 'form-control',
    			'onchange' => "{$onChange}",
    			]
    	);
    	 
    	$this->parts['{input}'] =
	    	$hiddenInput .
	    	"<div class='row'>" .
		    	"<div class='col-xs-2' style='padding-right:0px;'>{$hourInput}</div>" .
		    	"<div class='col-xs-2' style='padding:0px;'>{$minuteInput}</div>" .
		    	"<div class='col-xs-2' style='padding-left:0px;'>{$secondInput}</div>" .
		    	"<div class='col-xs-6' style='padding-right:0px;'>&nbsp;</div>" .
	    	"</div>";
    	return $this;
    }
    
    /**
     * Display the image currently set as well as the image selector
     * 
     * @param array $options
     * @return \yii\widgets\static
     */
    public function s3FileInput($options = [])
    {
    	if ($this->inputOptions !== ['class' => 'form-control']) {
    		$options = array_merge($this->inputOptions, $options);
    	}
    	$this->adjustLabelFor($options);
    	$value = Html::getAttributeValue($this->model, $this->attribute);
    	Html::addCssClass($options, 'form-control');
    	
    	$html = "";
    	if (!empty($value))
    		$html .= \Yii::$app->formatter->asS3File($value);
    	
    	$this->parts['{input}'] = $html . Html::activeFileInput($this->model, $this->attribute, $options);
		return $this;
    }
    
    /**
     * Display the image currently set as well as the image selector
     * 
     * @param array $options
     * @return \yii\widgets\static
     */
    public function s3ImageInput($options = [])
    {
    	$this->enableClientValidation = $this->model->isNewRecord;
    	
    	if ($this->inputOptions !== ['class' => 'form-control']) {
    		$options = array_merge($this->inputOptions, $options);
    	}
    	$this->adjustLabelFor($options);
    	$value = Html::getAttributeValue($this->model, $this->attribute);
    	Html::addCssClass($options, 'form-control');
    	$options['style'] = 'width:auto;';
    	$options['value'] = '';
    	
    	$html = "";
    	if (!empty($value))
    		$html .= \Yii::$app->formatter->asS3Image($value);
    	
    	$this->parts['{input}'] = $html . Html::activeFileInput($this->model, $this->attribute, $options);
		return $this;
    }
    
    /**
     * Display an email input field
     * 
     * @param array $options
     * @return \yii\widgets\static
     */
    public function weekdayInput($options = [])
    {
    	return $this->dropDownList([
    		1 => 'LUN',
    		2 => 'MAR',
    		3 => 'MIE',
    		4 => 'JUE',
    		5 => 'VIE',
    		6 => 'SAB',
    		0 => 'DOM',
    	], $options);
    }
    
    /**
     * Display an email input field
     * 
     * @param array $options
     * @return \yii\widgets\static
     */
    public function emailInput($options = [])
    {
    	return $this->input('email', $options);
    }
    
    /**
     * Display an input video
     * 
     * @param array $options
     * @return \common\yii\widgets\ActiveField
     */
    public function videoInput($options = [])
    {
    	if ($this->inputOptions !== ['class' => 'form-control']) {
    		$options = array_merge($this->inputOptions, $options);
    	}
    	$this->adjustLabelFor($options);
    	$value = Html::getAttributeValue($this->model, $this->attribute);
    	 
    	$html = "";
    	if (!empty($value))
    		$html .= \Yii::$app->formatter->asVideo($value, ['responsive' => true]);
    	 
    	Html::addCssClass($options, 'form-control');
    	$options['rows'] = 8;
    		 
    	$this->parts['{input}'] =
    		"<div class='row'>" .
    			"<div class='col-xs-6 col-sm-8'>" . Html::activeTextarea($this->model, $this->attribute, $options) . "</div>" .
    			"<div class='col-xs-6 col-sm-4'>{$html}</div>" .
    		"</div>"; 
    	return $this;
    }
    
    /**
     * Return a tinymce editor
     * 
     * @param array $options
     * @return \yii\widgets\static
     */
    public function htmlInput($options = [])
    {
    	if ($this->model instanceof \orm\cine\NewsStory)
    	{
    		$inputId = \yii\helpers\Html::getInputId($this->model, $this->attribute);
    		$previewId = $inputId . "-preview";
    		$html = "<style>#{$previewId}{padding:5px;margin-bottom:15px;width:100%;border:1px solid #ccc;height:450px;overflow:auto;}#{$previewId} img{max-width:100%;}</style>";
    		$html .= "<div class='form-group'>";
    		$html .= "<label class='col-sm-2 control-label'>" . $this->model->getAttributeLabel($this->attribute) . "</label>";
    		$html .= "<div class='col-sm-10'>";
	    		$html .= "<div class='row'>";
	    			$html .= "<div class='col-xs-6'>";
	    			$html .= \yii\helpers\Html::activeTextarea($this->model, $this->attribute, ['rows' => 25, 'class' => 'form-control', 'onkeyup' => "$('#{$previewId}').html($(this).val());"]);
	    			$html .= "</div>";
	    			$html .= "<div class='col-xs-6'>";
	    			$html .= "<div id='{$previewId}'></div>";
	    			$html .= "</div>";
	    		$html .= "</div>"; // row
    		$html .= "</div>";
    		$html .= "</div>";
    		\Yii::$app->view->registerJs('$(document).ready(function(){$("#'.$previewId.'").html($("#'.$inputId.'").val());});', View::POS_END);
    		return $html;
    	}
    	return $this->widget(\letyii\tinymce\Tinymce::className(), ArrayHelper::merge([
    		'configs' => [
    			//'file_browser_callback' => new JsExpression('mceBrowserCallback'),
    			'plugins' => 'autoresize,table,preview,code,template,hr,image,link,lists,media,textcolor',
    			'convert_fonts_to_spans' => true,
    			'content_css' => implode(", ", ["//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css", "//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"]),
    			'valid_elements' => '*[*]',
    			'toolbar' => [
    				"undo redo | styleselect | bold italic underline | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview code",
    				//"hr  template ",
    			],
    		],
    	], $options));
    }
}
