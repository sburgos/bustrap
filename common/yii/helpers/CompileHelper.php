<?php
namespace common\yii\helpers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Inflector;

class CompileHelper
{
	/**
	 * Compile a less file into a css file. You can pass key value
	 * pairs to the less variables array to specify global variables
	 * 
	 * $lessVariables = [
	 * 		'brand-primary' => '#e98d05',
	 * ]
	 * 
	 */
	public static function lessFile($src, $dest, $lessVariables =[],
		$lesscBinary = "/usr/local/bin/node /usr/local/bin/lessc")
	{
		$lessOptions = "";
		foreach ($lessVariables as $key => $value)
			$lessOptions .= " --modify-var='{$key}={$value}'";
		
		$command = strtr("{$lesscBinary} --clean-css='--compatibility=ie8 --advanced' '{from}' '{to}' --no-color{$lessOptions}", [
			'{from}' => escapeshellarg($src),
			'{to}' => escapeshellarg($dest),
		]);
		$descriptor = [
			1 => ['pipe', 'w'],
			2 => ['pipe', 'w'],
		];
		$pipes = [];
		$proc = proc_open($command, $descriptor, $pipes);
		$stdout = stream_get_contents($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		foreach ($pipes as $pipe) {
			fclose($pipe);
		}
		$status = proc_close($proc);

		if ($status === 0) {
			Yii::trace("Converted $src into $dest:\nSTDOUT:\n$stdout\nSTDERR:\n$stderr", __METHOD__);
		} elseif (YII_DEBUG) {
			throw new \Exception("AssetConverter command '$command' failed with exit code $status:\nSTDOUT:\n$stdout\nSTDERR:\n$stderr");
		} else {
			Yii::error("AssetConverter command '$command' failed with exit code $status:\nSTDOUT:\n$stdout\nSTDERR:\n$stderr", __METHOD__);
		}
	}
}