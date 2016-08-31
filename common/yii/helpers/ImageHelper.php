<?php
namespace common\yii\helpers;

use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Color;

class ImageHelper
{
	/**
	 * Convert multiple images and returns an array with the tempName and
	 * contentType of each image
	 * 
	 * @param string $imageFileName // full file name of the image
	 * @param string $extension // extension of the image
	 * @param [] $formats // Array of formats with:
	 * 		filter => @see convert
	 * 		extension => @see convert 
	 * 		saveOptions => @see convert
	 * @return null | []
	 */
	public static function convertMulti($fileName, $extension, $formats)
	{
		$images = [];
		$failed = false;
		foreach ($formats as $formatId => $formatInfo)
		{
			if (!is_array($formatInfo))
				$formatInfo = [];
			$image = static::convert(
				$fileName, 
				$extension, 
				ArrayHelper::getValue($formatInfo, 'filter', null),
				ArrayHelper::getValue($formatInfo, 'extension', null),
				ArrayHelper::getValue($formatInfo, 'saveOptions', [])
			);
			if ($image === null) {
				$failed = true;
				break;
			}
			$images[$formatId] = $image;
		}
		
		// If one of the formats failed then remove all of the previous
		// images that where created
		if ($failed) {
			static::removeConvertMulti($images);
			return null;
		}
		
		// Return the converted images
		return $images;
	}
	
	/**
	 * Remove images returned by the convertMulti method
	 * 
	 * @param [] $images
	 */
	public static function removeConvertMulti($images)
	{
		if (is_array($images))
		{
			foreach ($images as $image)
				static::removeConvert($image);
		}
	}
	
	/**
	 * Remove images returned by the convertMulti method
	 * 
	 * @param [] $images
	 */
	public static function removeConvert($image)
	{
		if (is_array($images))
		{
			if (isset($imgInfo['tempName']) && file_exists($imgInfo['tempName']))
				@unlink($imgInfo['tempName']);
		}
	}
	
	/**
	 * Convert an image by applying filters
	 * 
	 * Filter must be a callable function like
	 * 		function (Imagine\Image\ManipulatorInterface $img)
	 * 		{
	 * 			// Transform the $img
	 * 			return $img;
	 * 		}
	 * 
	 * @param string $fileName // Original file name of the image
	 * @param string $origExtension // Original image extension
	 * @param callable $filter // Callable function (optional)
	 * @param string $destExtension // Destination extension (optional)
	 * @param [] $saveOptions // Save options @see Imagine
	 * @return null | []
	 */
	public static function convert($fileName, $origExtension, 
		$filter = null, $destExtension = null, $saveOptions = [])
	{
		// Make sure that the image exists
		if (!file_exists($fileName))
			return null;
		
		// Validate the original extension
		$origExtension = strtolower($origExtension);
		if (!in_array($origExtension, ['jpg', 'png', 'gif', 'jpeg'])) {
			die("ERROR 1");
			return null;
		}
		
		// Validate extension
		$destExtension = ($destExtension !== null) ? strtolower($destExtension) : $origExtension;
		if (!in_array($destExtension, ['jpg', 'png', 'gif', 'jpeg'])) {
			die("ERROR 2");
			return null;
		}
		
		// Determine the content type
		$contentType = "image/{$destExtension}";
		if ($destExtension == 'jpg')
			$contentType = "image/jpeg";
		if ($destExtension == 'jpeg')
			$destExtension = 'jpg';
		
		// Make sure save options is an array
		if (!is_array($saveOptions))
			$options['saveOptions'] = [];
		
		// Load the image with Imagine and create a copy
		/* @var $img ManipulatorInterface */
		try {
			$img = \yii\imagine\Image::getImagine()
				->open($fileName)
				->copy();
		
			// Apply the filter callback
			if ($filter !== null && is_callable($filter))
			{
				$img = call_user_func($filter, $img);
				if (!($img instanceof ManipulatorInterface)) {
					die("ERROR 3");
					return null;
				}
			}
				
			// Calculate a new unique temp name
			$k = 1;
			$tempName = $fileName . rand(1000,9999) . $k . "." . $destExtension;
			while (file_exists($tempName)) {
				$k++;
				$tempName = $fileName . rand(1000,9999) . $k . "." . $destExtension;
			}
			
			// Save the image
			$img = $img->save($tempName, $saveOptions);
			
			return [
				'tempName' => $tempName,
				'extension' => $destExtension,
				'contentType' => $contentType,
			];
		}
		catch (\Exception $ex) {
			die("ERROR 4");
		}
			
		return null;
	}
	
	/**
	 * Create a thumbnail image
	 *
	 * @param ManipulatorInterface $image
	 * @param int $width
	 * @param int $height
	 * @param string $mode
	 * @return ManipulatorInterface
	 */
	public static function thumb($image, $width, $height, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND)
	{
		$box = new Box($width, $height);
	
		if (($image->getSize()->getWidth() <= $box->getWidth() && $image->getSize()->getHeight() <= $box->getHeight()) || (!$box->getWidth() && !$box->getHeight())) {
			return $image;
		}
	
		$image = $image->thumbnail($box, $mode);
	
		// create empty image to preserve aspect ratio of thumbnail
		$thumb = \yii\imagine\Image::getImagine()->create($box, new Color('FFF', 100));
	
		// calculate points
		$size = $image->getSize();
	
		$startX = 0;
		$startY = 0;
		if ($size->getWidth() < $width) {
			$startX = ceil($width - $size->getWidth()) / 2;
		}
		if ($size->getHeight() < $height) {
			$startY = ceil($height - $size->getHeight()) / 2;
		}
	
		$thumb->paste($image, new Point($startX, $startY));
	
		return $thumb;
	}
	
	/**
	 * Make sure the image fits inside the specified dimensions
	 *
	 * @param ManipulatorInterface $image
	 * @param int $width
	 * @param int $height
	 * @return ManipulatorInterface
	 */
	public static function contain($image, $width, $height)
	{
		$size =	$image->getSize();
		if ($size->getWidth() <= $width && $size->getHeight() <= $height)
			return $image;
	
		$newSize = $size->widen($width);
		if ($newSize->getHeight() > $height)
			$newSize = $size->heighten($height);
	
		return $image->resize($newSize);
	}
}
