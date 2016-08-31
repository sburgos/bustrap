<?php
namespace common\yii\helpers;

use orm\v1\admin\BinInfo;

class Binlist
{
	const EXPIRATION_IN_HOURS = 720; // 30 days
	
	/**
	 * Get the information for a specific bin
	 *
	 * @param string $from
	 * @param string $to
	 * @return BinInfo
	 */
	public static function get($bin)
	{
		$bin = substr($bin, 0, 6);

		// Try to find an existing bin
		$binInfo = BinInfo::findOne($bin);

		// If not found on the database or if it has expired then
		// fetch from api
		if ($binInfo === null || $binInfo->expiration <= date('Y-m-d H:i:s'))
		{
			$binInfoApi = null;
			
			// Get from the api from bin db
			try {
				$bindDbInfoXML = @file_get_contents("https://members2.bindb.com/portalv2/api/bin_api.php?api_key=52d5dff874e803650f2e44532976489e&bin={$bin}");
				if (!empty($bindDbInfoXML))
				{
					$parsedXML = @json_decode(@json_encode(simplexml_load_string($bindDbInfoXML)), true);
					if (!isset($parsedXML['bin']))
					{
						if (isset($parsedXML['code']) && ($parsedXML['code'] == '105' || $parsedXML['code'] == '106'))
						{
							// Do nothing
						}
						else
						{
							SendAlert::gettingBinInfoFailed($bin, [
								"msg" => "No se pudo obtener la informacion de la tarjeta desde bindb.",
								"bin" => $bin,
								"response" => $bindDbInfoXML,
							]);
						}
					}
					else
					{
						$binInfoApi = [
							'bin' => $parsedXML['bin'],
							'brand' => $parsedXML['brand'],
							'subBrand' => is_string($parsedXML['level']) ? $parsedXML['level'] : "??",
							'countryCode' => $parsedXML['country_iso'],
							'countryName' => $parsedXML['isocountry'],
							'bankName' => $parsedXML['bank'],
							'cardType' => is_string($parsedXML['type']) ? $parsedXML['type'] : "??",
							'cardCategory' => is_string($parsedXML['type']) ? $parsedXML['type'] : "??",
							'latitude' => "0.01",
							'longitude' => "0.01",
						];
						// Make sure everything is a string
						foreach ($binInfoApi as $key => $value)
						{
							if (!is_string($value))
								$binInfoApi[$key] = "??";
						}
					}
				}
			}
			catch (\Exception $ex) {
				SendAlert::gettingBinInfoFailed($bin, [
					"msg" => "Ocurrio un error al consultar bin db",
					"bin" => $bin,
					"ex" => $ex->getMessage(),
					'trace' => $ex->getTraceAsString(),
				]);
				$binInfoApi = null;
			}

			// Make sure something was obtained
			if (empty($binInfoApi))
			{
				// If not set then create a new one empty
				if ($binInfo === null)
				{
					$binInfo = new BinInfo();
					$binInfo->bin = $bin;
					$binInfo->brand = "??";
					$binInfo->subBrand = "??";
					$binInfo->countryCode = "??";
					$binInfo->countryName = "??";
					$binInfo->bankName = "??";
					$binInfo->cardType = "??";
					$binInfo->cardCategory = "??";
					$binInfo->latitude = "";
					$binInfo->longitude = "";
					$binInfo->expiration = date('Y-m-d H:i:s');
				}
				return $binInfo;
			}

			// Update the database record or create a new one
			if ($binInfo === null) {
				$binInfo = new BinInfo();
			}
			$binInfo->bin = $bin;
			$binInfo->brand = $binInfoApi['brand'];
			$binInfo->subBrand = $binInfoApi['subBrand'];
			$binInfo->countryCode = $binInfoApi['countryCode'];
			$binInfo->countryName = $binInfoApi['countryName'];
			$binInfo->bankName = $binInfoApi['bankName'];
			$binInfo->cardType = $binInfoApi['cardType'];
			$binInfo->cardCategory = $binInfoApi['cardCategory'];
			$binInfo->latitude = $binInfoApi['latitude'];
			$binInfo->longitude = $binInfoApi['longitude'];
			$binInfo->expiration = date('Y-m-d H:i:s', time() + (3600 * static::EXPIRATION_IN_HOURS));
			if (!$binInfo->save()) {
				SendAlert::savingToDataBaseFailed($binInfo);
			}
		}

		return $binInfo;
	}
}