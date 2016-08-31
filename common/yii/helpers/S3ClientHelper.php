<?php
namespace common\yii\helpers;

use Aws\S3\S3Client;

class S3ClientHelper
{
	const S3KEY_NAME = 'key';
	const S3URL_NAME = 'url';
	const S3BUC_NAME = 'buc';
	
	public static $s3Client = null;
	
	public static $bucketName = null;
	
	/**
	 * Get the S3 Client
	 *
	 * @return \Aws\S3\S3Client
	 */
	public static function getS3Client($region = null)
	{
		if (static::$s3Client === null)
		{
			if (empty($region))
			{
				$region = getenv('AWS_S3_REGION');
				if (empty($region))
					$region = 'us-west-2';
			}
			static::$s3Client = S3Client::factory([
				'region' => $region,
			]);
		}
		return static::$s3Client;
	}
	
	/**
	 * Get the bucket name
	 *
	 * @return string
	 */
	public static function getBucketName()
	{
		if (static::$bucketName === null)
		{
			static::$bucketName = getenv('AWS_S3_BUCKET');
			if (empty(static::$bucketName))
				static::$bucketName = 'papaya-static-' . YII_ENV;
		}
		return static::$bucketName;
	}
	
	/**
	 * Push a file to S3.
	 *
	 * It returns an array with the 'key' and 'url' of the
	 * uploaded file.
	 *
	 * If it fails it returns false
	 *
	 * @param string $s3Key
	 * @param string $sourceFile
	 * @param string $contentType
	 * @return false|array
	 */
	public static function upload($s3Key, $sourceFile, $contentType, $bucket = null)
	{
		try {
			$params = [
				'Bucket' => $bucket === null ? static::getBucketName() : $bucket,
				'Key' => $s3Key,
				'SourceFile' => $sourceFile,
				'ContentType' => $contentType,
				'ACL' => 'public-read',
			];
			$result = static::getS3Client()->putObject($params);
			$url = $result['ObjectURL'];
				
			// Remove the http or https from the url
			$dotsPos = strpos($url, ":");
			if ($dotsPos !== false)
				$url = substr($url, $dotsPos + 1);
				
			// Return the s3 file info
			return [
				static::S3KEY_NAME => $s3Key,
				static::S3BUC_NAME => $bucket === null ? static::getBucketName() : $bucket,
				static::S3URL_NAME => $url,
				'size' => filesize($sourceFile),
			];
		}
		catch (\Exception $ex) {
			@error_log($ex->getMessage() . " Trace:" . $ex->getTraceAsString());
		}
		return false;
	}
	
	/**
	 * Retrieve an object's Metadata from S3
	 *
	 * @param string $s3Key
	 * @param string $bucket
	 * @return string
	 */
	public static function exists($s3Key, $bucket = null)
	{
		try {
			return static::getS3Client()->doesObjectExist(
				$bucket === null ? static::getBucketName() : $bucket,
				$s3Key
			);
		}
		catch (\Exception $ex) {
			@error_log($ex->getMessage() . " Trace:" . $ex->getTraceAsString());
		}
		return null;
	}
	
	/**
	 * Retrieve an object from S3
	 *
	 * @see http://docs.aws.amazon.com/aws-sdk-php/latest/class-Aws.S3.S3Client.html#_getObject
	 *
	 * @param string $s3Key
	 * @param string $bucket
	 * @return object
	 */
	public static function getObject($s3Key, $bucket = null)
	{
		try {
			return static::getS3Client()->getObject([
				'bucket' => $bucket === null ? static::getBucketName() : $bucket,
				'key' => $s3Key,
			]);
		}
		catch (\Exception $ex) {
			@error_log($ex->getMessage() . " Trace:" . $ex->getTraceAsString());
		}
		return null;
	}
	
	/**
	 * Retrieve an object's URL from S3
	 *
	 * @param string $s3Key
	 * @param string $bucket
	 * @param int $expiration
	 * @return string
	 */
	public static function getUrl($s3Key, $bucket = null, $expiration = null)
	{
		try {
			return static::getS3Client()->getObjectUrl(
				$bucket === null ? static::getBucketName() : $bucket,
				$s3Key,
				$expiration
			);
		}
		catch (\Exception $ex) {
			@error_log($ex->getMessage() . " Trace:" . $ex->getTraceAsString());
		}
		return null;
	}
	
	/**
	 * Delete an object from S3
	 *
	 * @param string $s3Key
	 * @return boolean
	 */
	public static function deleteObject($s3Key, $bucket = null)
	{
		try {
			static::getS3Client()->deleteObject([
				'Bucket' => $bucket === null ? static::getBucketName() : $bucket,
				'Key' => $s3Key,
			]);
			return true;
		}
		catch (\Exception $ex) {
			@error_log($ex->getMessage() . " Trace:" . $ex->getTraceAsString());
		}
		return false;
	}
}
