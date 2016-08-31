<?php
namespace common\yii\helpers;

use yii\helpers\BaseArrayHelper;

class ArrayHelper extends BaseArrayHelper
{
	/**
	 * Get a value from an array or an object
	 * 
	 * @param array|object $mixed
	 * @param string $key
	 * @param mixed $default
     * @return mixed
     */
	public static function getValueMixed($mixed, $key, $default = null)
	{
		if (!$mixed)
			return $default;
		if (is_array($mixed))
		{
			if (array_key_exists($key, $mixed))
				return $mixed[$key];
		}
		else if (is_object($mixed))
		{
			if (isset($mixed->{$key}))
				return $mixed->{$key};
			$methodName = "get" . ucfirst($key);
			if (method_exists($mixed, $methodName))
				return $mixed->{$methodName}();
		}
		return $default;
	}
	
	/**
	 * Implode an array of key value pairs
	 * 
	 * Eg. ['12' => 'abc', '13 => 'def']
	 * 
	 * implodeKeys(',', $a, '=')
	 * 
	 * "12=abc,13=def"
	 *
     * @param        $glue
     * @param        $array
     * @param string $separator
     * @return string
     */
	public static function implodeKeys($glue, $array, $separator = "=")
	{
		$str = "";
		$first = true;
		foreach ($array as $key => $value) {
			if (!$first) $str .= $glue;
			$str .= "{$key}{$separator}{$value}";
			$first = false;
		}
		return $str;
	}
	
	/**
	 * Remove some keys from an array
	 * 
	 * @param array $array
	 * @param array $keys
	 * @return array
	 */
	public static function removeKeys($array, $keys)
	{
		foreach ($keys as $key)
		{
			if (array_key_exists($key, $array))
				unset($array[$key]);
		}	
		return $array;
	}
	
	/**
	 * Index by a key that can be repeated. If the innerKey is
	 * specified then the inner arrays will be indexed by that key
	 * 
	 * Returns an array of arrays
	 * 
	 * @param array $array
	 * @param string $key
	 * @param string $innerKey
     * @return array
     */
	public static function indexMulti($array, $key, $innerKey = null)
	{
		$res = [];
		if (!is_array($array))
			return $res;
		
		foreach ($array as $value) 
		{
			if (!array_key_exists($key, $value)) continue;
			if (!array_key_exists($value[$key], $res))
				$res[$value[$key]] = [];
			if ($innerKey === null)
				$res[$value[$key]][] = $value;
			else 
				$res[$value[$key]][$value[$innerKey]] = $value;
		}
		return $res;
	}	
	
	/**
	 * Allow to get multiple columns as if it was only one using the format
	 * and keys as sprintf
	 * @param string $array
	 * @param string $keys
	 * @param string $format
	 * @param bool $keepKeys
	 * @return multitype:|multitype:unknown mixed
	 */
	public static function getColumnMulti($array, $format, $keys, $keepKeys = true)
	{
		$res = [];
		if (!is_array($array))
			return $res;
		
		foreach ($array as $k => $element) 
		{
			$valueKeys = [$format];
			foreach ($keys as $key)
				$valueKeys[] = static::getValue($element, $key);
			$value = call_user_func_array("sprintf", $valueKeys);
			if ($keepKeys)
				$res[$k] = $value;
			else
				$res[] = $value;
		}
		
		return $res;
	}
	
	/**
	 * Build a numeric list of values
	 *  
	 * @return Ambigous <multitype:, string>
	 */
	public static function buildNumeric($from, $to, $step = 1, $format = "%02d", $indexed = true, $indexByValue = true, $emptyOption = null)
	{
		$from = intval($from);
		$to = intval($to);
		$step = intval($step);
		if ($step == 0 || ($from < $to && $step <= 0) || ($from > $to && $step >= 0)) 
			return [];
		$res = [];
		if ($emptyOption !== null)
		{
			if (is_array($emptyOption))
			{
				foreach ($emptyOption as $key => $value)
					$res[$key] = $value;
			}
			else 
				$res[""] = $emptyOption;
		}
		for ($k=$from; $k<=$to; $k+=$step) {
			$value = sprintf($format, $k);
			if ($indexed)
				$res[$indexByValue ? $value : $k] = $value;
			else
				$res[] = $value;
		}
		return $res;
	}
	
	/**
	 * Convert an array to it's php string representation
	 * 
	 * @param array $arr
	 * @return string
	 */
	public static function toStringPhpRepresentation($arr)
	{
		$pairs = [];
		foreach ($arr as $a => $b) {
			if (is_array($b))
				$pairs[] = "'$a' => " . static::toStringPhpRepresentation($b);
			else 
				$pairs[] = "'$a' => '" . str_replace("'", "\\'", $b) . "'";
		}
		
		return '[' . implode(', ', $pairs) . ']';
	}
}