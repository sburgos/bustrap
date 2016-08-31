<?php
namespace common\yii\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;
use common\yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use common\yii\helpers\S3ClientHelper;

class S3UploadFileBehavior extends Behavior
{
	public $columns = [];
	
	public function events()
	{
		return [
			BaseActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
			BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
			BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
			BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
			BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
		];
	}
	
	/**
	 * Before validation make sure that we obtain the uploaded files
	 * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeValidate($event)
    {
    	$model = $event->sender;
    	foreach ($this->columns as $attribute => $options)
    	{
    		// Attempt to the the file if it has not already been obtained
    		if (!($model->$attribute instanceof UploadedFile))
    		{
    			$oldAttribute = $model->$attribute;
    			
    			// Get the file from the $_FILES[formName][attribute]
    			$model->$attribute = UploadedFile::getInstance($model, $attribute);
    			
    			// If not found then try from $_FILES[attribute]
    			if (!($model->$attribute instanceof UploadedFile))
    				$model->$attribute = UploadedFile::getInstanceByName($attribute);

    			// No upload found set the previous value
    			if (!($model->$attribute instanceof UploadedFile))
    			{
    				if (!$model->isNewRecord)
    					$model->$attribute = $model->getOldAttribute($attribute);
    				else
    					$model->$attribute = $oldAttribute;
    			}
    		}
    	}
    }
    
    /**
     * Build the name based on the options specified
     * 
     * Available options:
     * 	name => 'name_in_{modelColumnName}_format_no_extension'
     *  addTime => If true will add time() at the end of the name
     *  addExtension => If true will add .ext for the current file extension
     *  slugify => If true it will make a slug of the resuling name
     * 
     * @param [] $options
     * @param Model $model
     * @param string $fileAttribute // The name of the model attribute with file
     * @return string
     */
    public function buildName($options, $model, $fileAttribute)
    {
    	$options = array_merge([
    		'name' => $fileAttribute,
    		'addTime' => true,
    		'addExtension' => true,
    		'slugify' => true,
    	], $options);
    	$name = $options['name'];
    	foreach ($model->attributes() as $attr)
    		$name = str_replace("{{$attr}}", $model->$attr, $name); 
    	if ($options['addTime'])
    		$name .= "-" . time();
    	if ($options['slugify']) {
    		$parts = explode("/", $name);
    		foreach ($parts as $index => $part)
    			$parts[$index] = Inflector::slug($part);
    		$name = implode("/", $parts);
    	}
    	if (trim($name) == '')
    		$name = Inflector::slug(uniqid());
    	if ($options['addExtension'])
    		$name .= "." . strtolower($model->$fileAttribute->extension);
    	return $name;
    }
    
    /**
     * Upload all the files that are pending upload
     * 
     * @param Event $event
     */
    protected function __uploadFiles($event)
    {
    	$model = $event->sender;
    	foreach ($this->columns as $attribute => $options)
    	{
    		if ($model->$attribute instanceof UploadedFile)
    		{
    			/* @var $file UploadedFile */
    			$file = $model->$attribute;
    			$saveName = $this->buildName($options, $model, $attribute);
    			$result = S3ClientHelper::upload($saveName, $file->tempName, $file->type);
    			if ($result !== false && $result !== null)
    				$model->$attribute = @json_encode($result);
    			else
    				$model->$attribute = $model->getOldAttribute($attribute);
    		}
    	}
    }
	
	/**
	 * Upload files
	 * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeInsert($event)
    {
        $this->__uploadFiles($event);
    }
    
    /**
     * Upload files
     * 
     * @param Event $event
     * @inheritdoc
     */
    public function beforeUpdate($event)
    {
    	$this->__uploadFiles($event);
    }
    
    /**
     * Delete previous files
     * 
     * @param \yii\db\AfterSaveEvent $event
     */
    public function afterUpdate($event)
    {
    	$model = $event->sender;
    	$changedAttributes = $event->changedAttributes;
    	foreach ($this->columns as $attribute => $options)
    	{
    		// Only delete the old file if the column has changed
    		if (!array_key_exists($attribute, $changedAttributes))
    			continue;
    		
    		// Make sure that the column had a valid S3 file
    		$s3Info = @json_decode($changedAttributes[$attribute], true);
    		if (!$s3Info || !is_array($s3Info) || 
    			!isset($s3Info[S3ClientHelper::S3KEY_NAME]) ||
    			!isset($s3Info[S3ClientHelper::S3BUC_NAME]) ||
    			empty($s3Info[S3ClientHelper::S3BUC_NAME]))
    		{
	    		continue;
    		}
    		
    		// Delete the file from S3 only if in the same bucket
    		// as the one used by this environment. This makes sure
    		// that changes in dev do not delete files uploaded in production
    		// that are seen on a database copy in development
    		if ($s3Info[S3ClientHelper::S3BUC_NAME] == S3ClientHelper::getBucketName())
    		{
	    		S3ClientHelper::deleteObject(
	    			$s3Info[S3ClientHelper::S3KEY_NAME],
	    			$s3Info[S3ClientHelper::S3BUC_NAME]
	    		);
    		}
    	}
    }

    /**
     * Delete previous files
     * 
     * @param Event $event
     * @inheritdoc
     */
    public function afterDelete($event)
    {
    	$model = $event->sender;
    	foreach ($this->columns as $attribute => $options)
    	{
    		// Make sure that the column had a valid S3 file
    		$s3Info = @json_decode($model->$attribute, true);
    		if (!$s3Info || !is_array($s3Info) || 
    			!isset($s3Info[S3ClientHelper::S3KEY_NAME]) ||
    			!isset($s3Info[S3ClientHelper::S3BUC_NAME]) ||
    			empty($s3Info[S3ClientHelper::S3BUC_NAME]))
    		{
	    		continue;
    		}
    		
    		// Delete the file from S3 only if in the same bucket
    		// as the one used by this environment. This makes sure
    		// that changes in dev do not delete files uploaded in production
    		// that are seen on a database copy in development
    		if ($s3Info[S3ClientHelper::S3BUC_NAME] == S3ClientHelper::getBucketName())
    		{
	    		S3ClientHelper::deleteObject(
	    			$s3Info[S3ClientHelper::S3KEY_NAME],
	    			$s3Info[S3ClientHelper::S3BUC_NAME]
	    		);
    		}
    	}
    }
}