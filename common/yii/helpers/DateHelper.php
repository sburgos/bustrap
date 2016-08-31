<?php
namespace common\yii\helpers;

use common\yii\helpers\ArrayHelper;

class DateHelper
{
	/**
	 * Get all the available timezones with their full information
	 * compared to the UTC Timezone +0
	 * 
	 * You can specify a 2 letter ISO code for the country to return
	 * only the timezones in that country
	 * 
	 * @param string $countryCode
	 * @return []
	 */
	public static function getTimezones($countryCode = null, $sortByOffset = true)
	{
		$utcTime = new \DateTime(null, new \DateTimeZone('UTC'));
		$dtZeroSeconds = new \DateTime("@0");
		$timezones = [];
		$allTimezones = ($countryCode === null) ? 
			\DateTimeZone::listIdentifiers() : 
			\DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, strtoupper($countryCode)); 
		foreach ($allTimezones as $timezone)
		{
			$tz = new \DateTimeZone($timezone);
			$offset = $tz->getOffset($utcTime);
			$dtOffsetSeconds = new \DateTime("@" . $offset);
			$diffStr = $dtZeroSeconds->diff($dtOffsetSeconds)->format('%H:%I');
			$neg = $offset < 0 ? "-" : "";
			$location = $tz->getLocation();
			$timezones[$timezone] = [
				'name' => $tz->getName(),
				'shortName' => StringHelper::basename($tz->getName()),
				'countryCode' => $location['country_code'],
				'latitude' => $location['latitude'],
				'longitude' => $location['longitude'],
				'comments' => $location['comments'],
				'offset' => $offset,
				'offsetStr' => "{$neg}{$diffStr}",
			];
		}
		if ($sortByOffset) {
			uasort($timezones, function ($a, $b){
				if ($a['offset'] == $b['offset'])
					return strcmp($a['shortName'], $b['shortName']);
				return $a['offset'] - $b['offset'];
			});
		}
		return $timezones;
	}
	
	/**
	 * Get all the timezones grouped by countryCode
	 * 
	 * @param string $countryCode
	 * @param boolean $sortByOffset
	 * @return [][]
	 */
	public static function getTimezonesByCountry($countryCode = null, $sortByOffset = true)
	{
		return ArrayHelper::indexMulti(
			static::getTimezones($countryCode, $sortByOffset), 
			'countryCode', 
			'name'
		);
	}
	
	/**
	 * Get a plain list of timezones. This is used for dropdown lists
	 * 
	 * @param string $countryCode
	 * @param boolean $sortByOffset
	 * @return []
	 */
	public static function getTimezonesList($countryCode = null, $sortByOffset = true)
	{
		return ArrayHelper::getColumnMulti(
			static::getTimezones($countryCode, $sortByOffset),
			'%s (%s)',
			['name', 'offsetStr'],
			true
		);
	}
	
	/**
	 * Get a plain list of timezones. This is used for dropdown lists
	 * 
	 * @param string $countryCode
	 * @param boolean $sortByOffset
	 * @return []
	 */
	public static function getTimezonesListByCountry($countryCode = null, $sortByOffset = true)
	{
		$timezones = static::getTimezonesByCountry($countryCode, $sortByOffset);
		$res = [];
		foreach ($timezones as $country => $tz)
		{
			$res[$country] = ArrayHelper::getColumnMulti(
				$tz, 
				'%s (%s)', 
				['shortName', 'offsetStr'], 
				true
			);
		}
		return $res;
	}
}