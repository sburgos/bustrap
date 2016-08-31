<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\yii\i18n;

use yii\helpers\FormatConverter;
use yii\helpers\HtmlPurifier;
use common\yii\helpers\Html;
use yii\i18n\Formatter as YiiFormatter;
use yii\helpers\VarDumper;

/**
 * Formatter provides a set of commonly used data formatting methods.
 *
 * The formatting methods provided by Formatter are all named in the form of `asXyz()`.
 * The behavior of some of them may be configured via the properties of Formatter. For example,
 * by configuring [[dateFormat]], one may control how [[asDate()]] formats the value into a date string.
 *
 * Formatter is configured as an application component in [[\yii\base\Application]] by default.
 * You can access that instance via `Yii::$app->formatter`.
 *
 * The Formatter class is designed to format values according to a [[locale]]. For this feature to work
 * the [PHP intl extension](http://php.net/manual/en/book.intl.php) has to be installed.
 * Most of the methods however work also if the PHP intl extension is not installed by providing
 * a fallback implementation. Without intl month and day names are in English only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Enrica Ruedin <e.ruedin@guggach.com>
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class Formatter extends YiiFormatter
{	
    /**
     * Formats the value as an image tag.
     * @param mixed $value the value to be formatted.
     * @param array $options the tag options in terms of name-value pairs. See [[Html::img()]].
     * @return string the formatted result.
     */
    public function asS3Image($value, $options = [])
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        $imgInfo = @json_decode($value, true);
        if (empty($imgInfo) || !isset($imgInfo[\common\yii\helpers\S3ClientHelper::S3URL_NAME]))
        	return $this->nullDisplay;
        $style = "display:block;width:80px;height:80px;background-size:contain;background-position:center center;background-repeat:no-repeat;";
        
        $url = $imgInfo[\common\yii\helpers\S3ClientHelper::S3URL_NAME];
        return "<a href='{$url}' target='_blank' style='{$style}background-image:url({$url}?q=50&w=80)'></a>";
    }
    /**
     * Formats the value as an image tag.
     * @param mixed $value the value to be formatted.
     * @param array $options the tag options in terms of name-value pairs. See [[Html::img()]].
     * @return string the formatted result.
     */
    public function asS3File($value, $options = [])
    {
        if ($value === null) {
            return $this->nullDisplay;
        }
        $fileInfo = @json_decode($value, true);
        if (empty($fileInfo))
        	return $this->nullDisplay;
        $url = $fileInfo[\common\yii\helpers\S3ClientHelper::S3URL_NAME];
        $text = $url;
        
        // If isset options gridMode then just show an icon
        if (isset($options['gridMode']) && $options['gridMode'])
        	$text = "<span class='fa fa-download'></span>";
        
        // Return a link to the file
        return "<a href='{$url}' target='_blank'><span class='fa fa-download'></span></a>";
    }
    
    /**
     * Display as a week day
     * 
     * @param integer $value
     * @return string
     */
    public function asWeekday($value)
    {
    	if ($value === null) {
    		return $this->nullDisplay;
    	}
    	$days = [
    		1 => 'LUN',
    		2 => 'MAR',
    		3 => 'MIE',
    		4 => 'JUE',
    		5 => 'VIE',
    		6 => 'SAB',
    		0 => 'DOM',
    	];
    	if (array_key_exists($value, $days))
    		return $days[$value];
    	else
    		return $value;
    }
    
    /**
     * Display as a date time
     * (non-PHPdoc)
     * @see \yii\i18n\Formatter::asDatetime()
     */
    public function asDatetime($value, $format = null)
    {
    	try {
    		return parent::asDatetime($value, $format);
    	}
    	catch (\Exception $ex) {
    		return $value;
    	}
    }

    /**
     * Formats the value as HTML text inside a box
     * The value will be purified using [[HtmlPurifier]] to avoid XSS attacks.
     * Use [[asRaw()]] if you do not want any purification of the value.
     * @param string $value the value to be formatted.
     * @param array|null $config the configuration for the HTMLPurifier class.
     * @return string the formatted result.
     */
    public function asHtmlBox($value, $config = null)
    {
        return "<div style='border:1px solid #ccc;max-height:400px;overflow:auto;padding:10px;'>".
        	$this->asHtml($value, $config) . 
        	"</div>";
    }

    /**
     * Formats the value as JSON
     * 
     * @param string $value the value to be formatted.
     * @param array|null $config the configuration for the HTMLPurifier class.
     * @return string the formatted result.
     */
    public function asJson($value, $config = null)
    {
    	return "<div style='white-space:pre;'>" . json_encode(json_decode($value, true), JSON_PRETTY_PRINT) . "</div>";
    }
    
    /**
     * Formats a json encoded video info as a video
     * @param string $value
     * @param string $options
     */
    public function asVideo($value, $options = [])
    {
    	if ($value === null) {
    		return $this->nullDisplay;
    	}
    	$videoInfo = @json_decode($value, true);
    	if (empty($videoInfo))
    		return $this->nullDisplay;
    	$url = $videoInfo['url'];
    	$code = $videoInfo['code'];
    	
    	$video = "<div class='embed-responsive embed-responsive-4by3'>{$code}</div>";
    	$link = "<a href='{$url}' style='border-top:1px solid #fff;font-size:12px;text-align:center;display:block;background-color:#000;color:#ccc;' target='_blank'>LINK <span class='fa fa-external-link-square'></span></a>";
    	
    	// If isset options gridMode then just show an icon
    	if (isset($options['gridMode']) && $options['gridMode'])
    		return $video;
    	
    	$wrapperStyle = "width:300px;";
    	if (isset($options['responsive'])) {
    		$wrapperStyle = "";
    	}
    	
    	// Return a link to the file
    	return "<div style='{$wrapperStyle}'>{$video}{$link}</div>";
    }
}
