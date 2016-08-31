<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\yii\widgets;

use Yii;
use yii\base\Widget;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    /**
     * @var array the HTML attributes (name-value pairs) for the form tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [
    	'enctype' => 'multipart/form-data',
    ];
    
    /**
     * @var string the default field class name when calling [[field()]] to create a new field.
     * @see fieldConfig
     */
    public $fieldClass = 'common\yii\widgets\ActiveField';

    /**
     * @var integer a value between 1 and 12 for the bootstrap grid
     */
    public $labelColSize = 2;
    
    /**
     * @var boolean whether to enable client-side data validation.
     * If [[ActiveField::enableClientValidation]] is set, its value will take precedence for that input field.
     */
    public $enableClientValidation = true;
    /**
     * @var boolean whether to enable AJAX-based data validation.
     * If [[ActiveField::enableAjaxValidation]] is set, its value will take precedence for that input field.
     */
    public $enableAjaxValidation = false;
    /**
     * @var boolean whether to hook up yii.activeForm JavaScript plugin.
     * This property must be set true if you want to support client validation and/or AJAX validation, or if you
     * want to take advantage of the yii.activeForm plugin. When this is false, the form will not generate
     * any JavaScript.
     */
    public $enableClientScript = true;
    /**
     * @var array|string the URL for performing AJAX-based validation. This property will be processed by
     * [[Url::to()]]. Please refer to [[Url::to()]] for more details on how to configure this property.
     * If this property is not set, it will take the value of the form's action attribute.
     */
    public $validationUrl;
    /**
     * @var boolean whether to perform validation when the form is submitted.
     */
    public $validateOnSubmit = true;
    /**
     * @var boolean whether to perform validation when the value of an input field is changed.
     * If [[ActiveField::validateOnChange]] is set, its value will take precedence for that input field.
     */
    public $validateOnChange = true;
    /**
     * @var boolean whether to perform validation when an input field loses focus.
     * If [[ActiveField::$validateOnBlur]] is set, its value will take precedence for that input field.
     */
    public $validateOnBlur = false;
    /**
     * @var boolean whether to perform validation while the user is typing in an input field.
     * If [[ActiveField::validateOnType]] is set, its value will take precedence for that input field.
     * @see validationDelay
     */
    public $validateOnType = false;

    public function init()
    {
    	if (empty($this->fieldConfig)) 
    	{
    		$labelColSize = intval($this->labelColSize);
    		if ($labelColSize <= 0 || $labelColSize >= 11) {
    			$labelColSize = 12;
    			$inputColSize = 12;
    		}
    		else {
    			$inputColSize = 12 - $labelColSize;
    		}
    		$this->fieldConfig = [
    			'options' => [
    				'class' => 'form-group',
    			],
    			'template' => "{label}\n<div class='col-sm-{$inputColSize}'>{input}\n{hint}\n{error}</div>",
    			'labelOptions' => [
    				'class' => "col-sm-{$labelColSize}" . ($labelColSize < 12 ? " control-label" : ""),
    			]
    		];
    	}
    	$this->options['enctype'] = 'multipart/form-data';
    	parent::init();
    }
}
