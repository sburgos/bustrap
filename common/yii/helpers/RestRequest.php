<?php
namespace common\yii\helpers;

use yii\web\BadRequestHttpException;
use common\yii\exceptions\RestRequestException;

class RestRequest
{
	protected static $_config = [
		'id' => null,
		'secret' => null,
		'host' => null,
		'language' => 'es-PE',
		'connectionTimeout' => 20,
		'timeout' => 60,
	]; 

    /**
     * Set the connection settings
     *
     * setConfig([
     *    'language' => 'es-PE',
     *    'id' => 'demo',
     *    'secret' => 'demo',
     *    'host' => 'http://rest.papaya.vh',
     * ]);
     *
     * @param array $config
     */
    public static function setConfig($config = array())
    {
    	foreach ($config as $key => $value)
    	{
    		if (array_key_exists($key, static::$_config))
    			static::$_config[$key] = $value;
    	}
    }
    
    /**
     * Get the configuration for the request
     * @return multitype:NULL string
     */
    public static function getConfig()
    {
    	return static::$_config;
    }
    
    /**
     * Shortcut method to do a curl request via post
     * 
     * @param string $url
     * @param null|array $params
     * 
     * @return array
     * @throws \Exception
     */
    public static function post($url, $params = null)
    {
    	return static::_request($url, $params, 'post');
    }
    
    /**
     * Prepend the params to the url and return a valid url with
     * all the parameters attached
     * 
     * @param string $url
     * @param array $params
     * @return string
     */
    public static function generateGetUrl($url, $params = null)
    {
    	if (empty($params) || !is_array($params))
    		return $url;
    	
    	if (strrpos($url, "?") === false)
    		return $url . "?" . http_build_query($params);
    	else
    		return $url . "&" . http_build_query($params);
    }
    
    /**
     * Shortcut method to do a curl request via get
     * 
     * Params will be appended to the $url string
     * 
     * @param string $url
     * @param null|array $params
     * 
     * @return array
     * @throws \Exception
     */
    public static function get($url, $params = null)
    {
    	return static::_request(static::generateGetUrl($url, $params), null, 'get');
    }
    
    /**
     * Make a curl request to the server
     * @param unknown $path
     * @param string $params
     * @param string $method
     */
    protected static function _request($url, $params = null, $method = 'post')
    {
    	// Validate parameters
    	if (empty(static::$_config['id']) || empty(static::$_config['secret']) || empty(static::$_config['host']))
    	{
    		throw new BadRequestHttpException('You must specify id, secret, and host');
    	}
    	
    	// If url begins with 'http' then we will remove the first part
    	// of the url (same length as host) to make sure that we are
    	// always pointing to the configured host in the request
    	if (strtolower(substr($url, 0, 4)) == 'http')
    		$url = substr($url, strlen(static::$_config['host']));
    	
    	// Buils CURL headers
    	$headers = [
    		'Accept: application/json',
    		'Accept-Language: ' . static::$_config['language'],
    		'Fingerprint: CINEPAPAYA-WORKER',
    		'Client-Ip: 10.0.0.0',
    		'User-Agent: CINEPAPAYA-WORKER',
    	];
    	
    	// Set curl options
    	$options = [
    		CURLOPT_URL => rtrim(static::$_config['host'], '/') . '/' . ltrim($url, '/'),
    		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
    		CURLOPT_USERPWD => static::$_config['id'] . ":" . static::$_config['secret'],
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_CONNECTTIMEOUT => static::$_config['connectionTimeout'],
    		CURLOPT_TIMEOUT => static::$_config['timeout'],
    		CURLOPT_HTTPHEADER => $headers,
    		CURLOPT_SSL_VERIFYPEER => false,
    	];
    	if ($method == 'post' && !empty($params)) {
    		$options[CURLOPT_POST] = 1;
    		if (!empty($params))
    			$options[CURLOPT_POSTFIELDS] = http_build_query($params);
    	}
    	
    	// Build curl
    	$curl = curl_init();
    	curl_setopt_array($curl, $options);
    	
    	if(YII_ENV != 'prod')
    	{
    		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    	}
    	
    	$body = curl_exec($curl);
    	$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    	
    	//echo "<pre>";var_dump($body);die();
    	
    	curl_close($curl);

    	// Parse the body
    	$bodyParsed = json_decode($body, true);
    	 
    	// If the code is 200 then it is a success
    	if ($code >= 200 && $code <= 299)
    		return $bodyParsed;
    	
    	// Not 200? then throw an exception
    	throw new RestRequestException($code, $bodyParsed);
    } 
}