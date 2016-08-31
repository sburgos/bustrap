<?php
namespace common\yii\behaviors;

use Yii;
use yii\db\BaseActiveRecord;
use yii\base\Exception;
use yii\validators\UrlValidator;

/**
 * Class VideoBehavior
 *
 * @property ActiveRecord $owner
 */
class VideoBehavior extends \yii\base\Behavior
{
	public $columns = [];
	
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
			BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
			BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
		];
	}
	
	/**
	 * Before validation make sure that we have a valid url
	 * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeValidate($event)
    {
    	$model = $event->sender;
    	foreach ($this->columns as $col)
    	{
    		if (empty($model->$col))
    			$model->$col = $model->getOldAttribute($col);
    		else if ($model->$col === 'null')
    			$model->$col = "";
    	}
    }
	
	/**
	 * Make sure that the video is stored as data
	 * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeInsert($event)
    {
    	$model = $event->sender;
    	$urlValidator = new UrlValidator();
    	foreach ($this->columns as $col) {
    		if (!empty($model->$col) && $urlValidator->validate($model->{$col}))
    		{
	    		$data = static::getUrlData($model->{$col});
	    		if (!empty($data)) {
	    			$model->{$col} = json_encode($data);
	    		}
    		}
    	}
    }
	
	/**
	 * Make sure that the video is stores as data
	 * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeUpdate($event)
    {
    	/** @var $model \yii\db\ActiveRecord */
    	$model = $event->sender;
    	$dirtyColumns = $model->getDirtyAttributes();
    	$urlValidator = new UrlValidator();
    	foreach ($this->columns as $col)
    	{
    		if (array_key_exists($col, $dirtyColumns)) {
    			if (!empty($model->$col) && $urlValidator->validate($model->{$col})) {
    				$data = static::getUrlData($model->{$col});
	    			if (!empty($data)) {
	    				$model->{$col} = json_encode($data);
    				}
    			}
    		}
    	}
    }
    
    /**
     * Get the data for a specific url
     * 
     * Make sure that embed has been included in the autoloader
     * in your composer.json
     * 
     * {
     * 		"autoload": {
     * 			"psr-4": {"Embed\\": "common/yii/behaviors/videoBehavior/embed"}
     * 		}
     * }
     * 
     * @param unknown $url
     * @return NULL|multitype:unknown NULL multitype:NULL  Ambigous <string, NULL>
     */
    public static function getUrlData($url)
    {
    	if (stripos($url, "youtube") !== false)
    	{
    		$quest = strpos($url, "?");
    		$get = substr($url, $quest+1);
    		$parts = explode("&", $get);
    		$id = null;
    		foreach ($parts as $part)
    		{
    			$keypair = explode("=", $part);
    			if ($keypair[0] == "v")
    			{
    				$id = $keypair[1];
    				break;
    			}
    		}
    		if ($id !== null)
    		{
	    		return [
	    			'url' => $url,
	    			'code' => "<iframe width=\"480\" height=\"270\" src=\"//www.youtube.com/embed/{$id}?feature=oembed\" frameborder=\"0\" allowfullscreen></iframe>",
	    			'type' => "videos",
	    			'image' => "//i.ytimg.com/vi/{$id}/hqdefault.jpg",
	    			'imageWidth' => 480,
	    			'imageHeight' => 360,
	    			'width' => 480,
	    			'height' => 270,
	    			'aspectRatio' => 56.25,
	    			'provider' => "YouTube",
	    		];
    		}
    		else 
    			return null;
    	}
    	if (stripos($url, "vimeo") !== false)
    	{
    		$parts = explode("/", $url);
    		$id = array_pop($parts);
    		if ($id !== null)
    		{
    			$info = @json_decode(@file_get_contents('http://vimeo.com/api/v2/video/' . $id . '.json'), true);
    			return [
    				'url' => $url,
					'code' => "<iframe width=\"480\" height=\"270\" src=\"//player.vimeo.com/video/{$id}\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>",
					'type' => "videos",
					'image' => isset($info['thumbnail_medium']) ? $info['thumbnail_medium'] : null,
					'imageWidth' => isset($info['width']) ? $info['width'] : 200,
					'imageHeight' => isset($info['height']) ? $info['height'] : 150,
					'width' => isset($info['width']) ? $info['width'] : 480,
					'height' => isset($info['height']) ? $info['height'] : 270,
					'aspectRatio' => 56.25,
					'provider' => "Vimeo",
    			];
    		}
    		else
    			return null;
    	}
    	return null;
    }
}