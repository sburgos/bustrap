<?php
namespace common\yii\helpers;

class ExchangeRate
{
	const CACHE_KEY = 'PAPAYA_EXCHANGE_RATES';
	
	protected static $_all = null;

	/**
	 * Get the exchange rate from a currency
	 * to other currency.
	 *
	 * It will return false if the exchange is not available
	 *
	 * @param string $from
	 * @param string $to
	 * @return float|false
	 */
	public static function get($from, $to)
	{
		$all = static::all();
		$key = strtoupper($from . $to);
		if (!empty($all) && array_key_exists($key, $all))
			return $all[$key];
		return false;
	}

	/**
	 * Return all the exchange rates from the current currency to
	 * all the available currencies
	 *
	 * @param string $from
	 * @return null|array
	 */
	public static function allFrom($from)
	{
		$all = static::all();
		if (empty($all)) return null;
		$from = strtoupper($from);
		$result = [];
		foreach ($all as $key => $rate)
		{
			if (substr($key, 0, 3) == $from)
			{
				$result[substr($key, 3, 3)] = $rate;
			}
		}
		if (count($result) == 0)
			return null;
		return $result;
	}

	/**
	 * Convert a value from one currency to another
	 *
	 * Convert $x $inCurrency to its equivalent in $toCurrency
	 *
	 * @param number $x
	 * @param string $inCurrency
	 * @param string $toCurrency
	 * @return number|false
	 */
	public static function convert($x, $inCurrency, $toCurrency)
	{
		$rate = static::get($inCurrency, $toCurrency);
		if ($rate !== false)
			return floatval($x) * $rate;
		return false;
	}
	
	/**
	 * Clear cache for exchange rates. This is ideal when
	 * adding or removing a new currency to our system. But if
	 * currencies exist for a long period of time then this is not
	 * necessary at any point
	 */
	public static function clearCache()
	{
		$cache = \Yii::$app->getCache();
		$cache->delete(static::CACHE_KEY);
	}

	/**
	 * Get all the exchange rates in the format
	 *
	 * FFFTTT WHERE FFF is the From Currency code and TTT the To Currency Code
	 *
	 * It caches the query so it does not fetch exchange rates all the time
	 *
	 * @return []|null
	 */
	public static function all()
	{
		if (static::$_all !== null)
			return static::$_all;

		// Try to get all from cache
		$cache = \Yii::$app->getCache();
		$all = $cache->get(static::CACHE_KEY);
		if ($all === false || empty($all))
		{
			$all = null;
			$combinations = [];
			$currencies = [
				'ARS', 'BOB', 'BRL', 'CLP', 'COP', 'CRC', 'GTQ', 'HNL',
				'MXN', 'PAB', 'PEN', 'PYG', 'USD', 'UYU'
			];
			$currencies2 = ['USD'];
			foreach ($currencies as $cur)
			{
				foreach ($currencies2 as $cur2)
				{
					if ($cur != $cur2)
						$combinations[] = $cur . $cur2;
				}
			}

			$url = "https://query.yahooapis.com/v1/public/yql?q=";
			$query = 'select * from yahoo.finance.xchange where pair in ("' . implode('","', $combinations) . '")';
			$url .= urlencode($query);
			$url .= "&format=json";
			$url .= "&env=" . urlencode("store://datatables.org/alltableswithkeys");

			$res = @file_get_contents($url);
			if (!empty($res)) {
				try {
					$resDecoded = json_decode($res, true);
					if (isset($resDecoded['query']['results']['rate']))
					{
						if (is_array($resDecoded['query']['results']['rate']))
						{
							$all = [];
							foreach ($resDecoded['query']['results']['rate'] as $rateInfo)
							{
								$all[$rateInfo['id']] = floatval($rateInfo['Rate']);
							}
						}
					}
				}
				catch (\Exception $ex) {
				}
			}

			// Save to cache if could be obtained
			if (!empty($all))
			{
				// Add same to same conversions
				foreach ($currencies as $cur)
					$all[strtoupper($cur) . strtoupper($cur)] = 1;
				// Cache
				$cache->set(static::CACHE_KEY, $all, 3600 * 2); // Cache for 2 hours
				static::$_all = $all;
			}
		}
		return $all;
	}
}