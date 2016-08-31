<?php
namespace common\yii\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;
use common\yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use common\yii\helpers\S3ClientHelper;

class S3UploadImageBehavior extends S3UploadFileBehavior
{	
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
				/* @var $file \yii\web\UploadedFile */
				$file = $model->$attribute;
				$saveName = $this->buildName($options, $model, $attribute);
				$result = S3ClientHelper::upload($saveName, $file->tempName, $file->type);
				if ($result !== false && $result !== null)
				{
					$img = \yii\imagine\Image::getImagine()->open($file->tempName);
					$boxSize = $img->getSize();
					$result['extension'] = strtolower($file->extension);
					$result['width'] = $boxSize->getWidth();
					$result['height'] = $boxSize->getHeight();
					$result['urlS3'] = $result[S3ClientHelper::S3URL_NAME];
					$result[S3ClientHelper::S3URL_NAME] = "//" . rtrim(getenv('S3_URL_IMAGE_RESIZER_HOST'), "/") . "/" .
							$result[S3ClientHelper::S3BUC_NAME] . "/" . $result[S3ClientHelper::S3KEY_NAME]; 
					$model->$attribute = @json_encode($result);
				}
				else
					$model->$attribute = $model->getOldAttribute($attribute);
				@unlink($file->tempName);
			}
		}
	}
}