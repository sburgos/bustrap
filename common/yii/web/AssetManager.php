<?php
/**
 * Override the methods so the filemtime does not change the hash
 * of the published assets. This is so elastic beanstalk have
 * all of the same asset urls.
 */
namespace common\yii\web;

use Yii;
use yii\helpers\FileHelper;
use common\yii\helpers\StringHelper;
use yii\helpers\Inflector;

class AssetManager extends \yii\web\AssetManager
{
	protected function hash($path)
	{
		$path = substr($path, strlen(dirname(\Yii::$app->basePath)) + 1);
		$path = str_replace("\\", "-", str_replace("/", "-", $path));
		
		return sprintf('%x', crc32($path));
	}
	
	protected function publishFile($src)
	{
		$dir = $this->hash(dirname($src)/* . filemtime($src)*/);
		$fileName = basename($src);
		$dstDir = $this->basePath . DIRECTORY_SEPARATOR . $dir;
		$dstFile = $dstDir . DIRECTORY_SEPARATOR . $fileName;
	
		if (!is_dir($dstDir)) {
			FileHelper::createDirectory($dstDir, $this->dirMode, true);
		}
	
		if ($this->linkAssets) {
			if (!is_file($dstFile)) {
				symlink($src, $dstFile);
			}
		} elseif (@filemtime($dstFile) < @filemtime($src)) {
			copy($src, $dstFile);
			if ($this->fileMode !== null) {
				@chmod($dstFile, $this->fileMode);
			}
		}
	
		return [$dstFile, $this->baseUrl . "/$dir/$fileName"];
	}
	
	protected function publishDirectory($src, $options)
	{
		$dir = $this->hash($src/* . filemtime($src)*/);
		$dstDir = $this->basePath . DIRECTORY_SEPARATOR . $dir;
		if ($this->linkAssets) {
			if (!is_dir($dstDir)) {
				symlink($src, $dstDir);
			}
		} elseif (!is_dir($dstDir) || !empty($options['forceCopy']) || (!isset($options['forceCopy']) && $this->forceCopy)) {
			$opts = [
				'dirMode' => $this->dirMode,
				'fileMode' => $this->fileMode,
			];
			if (isset($options['beforeCopy'])) {
				$opts['beforeCopy'] = $options['beforeCopy'];
			} elseif ($this->beforeCopy !== null) {
				$opts['beforeCopy'] = $this->beforeCopy;
			} else {
				$opts['beforeCopy'] = function ($from, $to) {
					return strncmp(basename($from), '.', 1) !== 0;
				};
			}
			if (isset($options['afterCopy'])) {
				$opts['afterCopy'] = $options['afterCopy'];
			} elseif ($this->afterCopy !== null) {
				$opts['afterCopy'] = $this->afterCopy;
			}
			FileHelper::copyDirectory($src, $dstDir, $opts);
		}
	
		return [$dstDir, $this->baseUrl . '/' . $dir];
	}
	
	public function getPublishedPath($path)
	{
		$path = Yii::getAlias($path);
	
		if (isset($this->_published[$path])) {
			return $this->_published[$path][0];
		}
		if (is_string($path) && ($path = realpath($path)) !== false) {
			$base = $this->basePath . DIRECTORY_SEPARATOR;
			if (is_file($path)) {
				return $base . $this->hash(dirname($path)/* . filemtime($path)*/) . DIRECTORY_SEPARATOR . basename($path);
			} else {
				return $base . $this->hash($path/* . filemtime($path)*/);
			}
		} else {
			return false;
		}
	}
	
	public function getPublishedUrl($path)
	{
		$path = Yii::getAlias($path);
	
		if (isset($this->_published[$path])) {
			return $this->_published[$path][1];
		}
		if (is_string($path) && ($path = realpath($path)) !== false) {
			if (is_file($path)) {
				return $this->baseUrl . '/' . $this->hash(dirname($path)/* . filemtime($path)*/) . '/' . basename($path);
			} else {
				return $this->baseUrl . '/' . $this->hash($path/* . filemtime($path)*/);
			}
		} else {
			return false;
		}
	}
}