<?php
namespace modules\admin\controllers;

use Yii;
use yii\web\Controller;
use orm\admin\Language;
use common\yii\helpers\DateHelper;
use orm\admin\TimeZone;
use orm\admin\Currency;
use orm\admin\Country;
use orm\admin\Region;
use yii\helpers\Inflector;
use orm\admin\City;
use yii\helpers\VarDumper;

class InitDatabaseController extends Controller
{
	protected $_oldDb = null;
	protected $_oldBoliviaDb = null;
	
	
	public function getOldDb()
	{
		if ($this->_oldDb === null)
		{
			$OLD_DB_HOST = getenv('OLD_DB_HOST');
			$OLD_DB_USER = getenv('OLD_DB_USER');
			$OLD_DB_PASS = getenv('OLD_DB_PASS');
			
			$this->_oldDb = new \yii\db\Connection([
				'dsn' => 'mysql:host='. $OLD_DB_HOST . ';dbname=admin_cinepapaya',
				'username' => $OLD_DB_USER,
				'password' => $OLD_DB_PASS,
				'charset' => 'utf8',
			]);
		}
		return $this->_oldDb;
	}
	
	public function getOldBoliviaDB()
	{
		if ($this->_oldBoliviaDb === null)
		{
			$OLD_DB_HOST = getenv('OLD_DB_HOST');
			$OLD_DB_USER = getenv('OLD_DB_USER');
			$OLD_DB_PASS = getenv('OLD_DB_PASS');
				
			$this->_oldBoliviaDb = new \yii\db\Connection([
				'dsn' => 'mysql:host='. $OLD_DB_HOST . ';dbname=cinepapaya_bolivia',
				'username' => $OLD_DB_USER,
				'password' => $OLD_DB_PASS,
				'charset' => 'utf8',
			]);
		}
		return $this->_oldBoliviaDb;
	}
	
	public function actionIndex()
	{
		return $this->render('index', [
			'countries' => Country::find()->asArray()->indexBy('id')->all(),
		]);
	}
	
	/**
	 * Add the default languages
	 * 
	 * @return \yii\web\Response
	 */
	public function actionLanguages()
	{
		$items = [
			'es' => ['name'=>'Español'],
			'en' => ['name'=>'English'],
			'pt' => ['name'=>'Portuguese'],
		];
		
		$added = [];
		foreach ($items as $itemId => $item)
		{
			$row = Language::findOne($itemId);
			if (!$row) {
				$row = new Language($item);
				$row->id = $itemId;
				if ($row->save())
					$added[] = $item['name'];
			}
		}
		if (count($added) > 0)
			Yii::$app->getSession()->addFlash('success', 'Se agregaron: ' . implode(", ", $added));
		else
			Yii::$app->getSession()->addFlash('warning', 'Nada que agregar');
		 
		return $this->redirect(['index']);
	}
	
	/**
	 * Add all the timezones for the countries specified
	 * 
	 * @return \yii\web\Response
	 */
	public function actionTimezones()
	{
		$countries = [
			'pe' => 'Peru', 
			'cl' => 'Chile', 
			'co' => 'Colombia',
			'ar' => 'Argentina', 
			'bo' => 'Bolivia', 
			'ec' => 'Ecuador',
			'cr' => 'Costa rica', 
			'mx' => 'Mexico', 
			'pa' => 'Panama',
			'py' => 'Paraguay', 
			'uy' => 'Uruguay', 
			'br' => 'Brasil', 
			've' => 'Venezuela',
			'hn' => 'Honduras',
			'gt' => 'Guatemala',
			'sv' => 'El Salvador',
			'ni' => 'Nicaragua',
			'do' => 'República Dominicana',
			'pr' => 'Puerto rico',
		];
		 
		$items = [];
		foreach ($countries as $countryId => $countryName)
		{
			$tzones = DateHelper::getTimezones($countryId);
			foreach ($tzones as $tzoneId => $tzone)
			{
				$name = substr($countryName . ": " . str_replace("_", " ", $tzone['shortName']) . " (" . $tzone['offsetStr'] . ")", 0, 100);
				$items[$tzoneId] = ['name' => $name];
			}
		}
		
		$failed = 0;
		$added = 0;
		$updated = 0;
		foreach ($items as $itemId => $item)
		{
			$row = TimeZone::findOne($itemId);
			if (!$row) {
				$row = new TimeZone($item);
				$row->id = $itemId;
				if ($row->save())
					$added++;
				else
					$failed++;
			}
			else {
				$row->setAttributes($item);
				if (count($row->getDirtyAttributes()) > 0)
				{
					if ($row->save())
						$updated++;
					else
						$failed++;
				}
			}
		}
		
		if ($added + $updated + $failed == 0)
			Yii::$app->getSession()->addFlash('success', "No changes detected");
		if ($added > 0)
			Yii::$app->getSession()->addFlash('success', "{$added} timezones added");
		if ($updated > 0)
			Yii::$app->getSession()->addFlash('warning', "{$updated} timezones updated");
		if ($failed > 0)
			Yii::$app->getSession()->addFlash('error', "{$failed} timezones failed");
		return $this->redirect(['index']);
	}
	
	/**
	 * Add all the default currencies
	 * 
	 * @return \yii\web\Response
	 */
	public function actionCurrencies()
	{
		$items = [
			'USD' => ['name'=>'Dólares Americanos','symbol'=>'US$','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'PEN' => ['name'=>'Nuevos Soles','symbol'=>'S/.','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'CLP' => ['name'=>'Pesos Chilenos','symbol'=>'CL$','decimalPlaces'=>0,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'COP' => ['name'=>'Pesos Colombianos','symbol'=>'$','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'ARS' => ['name'=>'Pesos Argentinos','symbol'=>'$','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'BOB' => ['name'=>'Bolivianos','symbol'=>'Bs.','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'CRC' => ['name'=>'Colones','symbol'=>'₡','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'MXN' => ['name'=>'Pesos Mexicanos','symbol'=>'MEX$','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'PAB' => ['name'=>'Balboa','symbol'=>'B/.','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'PYG' => ['name'=>'Guaraní','symbol'=>'₲','decimalPlaces'=>0,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'UYU' => ['name'=>'Pesos Uruguayos','symbol'=>'$U','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'BRL' => ['name'=>'Reais','symbol'=>'R$','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'HNL' => ['name'=>'Lempira','symbol'=>'L','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'GTQ' => ['name'=>'Quetzales','symbol'=>'Q','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'SVC' => ['name'=>'Colone Salvadoreño','symbol'=>'¢','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],

			'VEF' => ['name'=>'Bolivar Fuerte','symbol'=>'Bs.F','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'NIO' => ['name'=>'Córdoba','symbol'=>'C$','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
			'DOP' => ['name'=>'Pesos Dominicanos','symbol'=>'RD$','decimalPlaces'=>2,'decimalSeparator'=>'.','thousandSeparator'=>','],
		];
			
		$added = [];
		foreach ($items as $itemId => $item)
		{
			$row = Currency::findOne($itemId);
			if (!$row) {
				$row = new Currency($item);
				$row->id = $itemId;
				if ($row->save())
					$added[] = $item['name'];
			}
		}
		if (count($added) > 0)
			Yii::$app->getSession()->addFlash('success', 'Se agregaron: ' . implode(", ", $added));
		else
			Yii::$app->getSession()->addFlash('warning', 'Nada que agregar');
			
		return $this->redirect(['index']);
	}
	
	/**
	 * Add all the default countries
	 * 
	 * @return \yii\web\Response
	 */
	public function actionCountries()
	{
		$items = [
			'ZZ' => ['oldIdUbigeo'=>'2533', 'name'=>'(Todos los paises)', 'timeZoneId' => 'America/Lima', 'asciiName' => 'Todos los paises', 'code3'=>'ZZZ','languageId'=>'es','currencyId'=>'USD', 'visible' => false],
			'PE' => ['oldIdUbigeo'=>'2533', 'name'=>'Perú', 'timeZoneId' => 'America/Lima', 'asciiName' => 'Peru', 'code3'=>'PER','languageId'=>'es','currencyId'=>'PEN'],
			'CL' => ['oldIdUbigeo'=>'4623', 'name'=>'Chile', 'timeZoneId' => 'America/Santiago', 'asciiName' => 'Chile', 'code3'=>'CHL','languageId'=>'es','currencyId'=>'CLP'],
			'CO' => ['oldIdUbigeo'=>'4626', 'name'=>'Colombia', 'timeZoneId' => 'America/Bogota', 'asciiName' => 'Colombia', 'code3'=>'COL','languageId'=>'es','currencyId'=>'COP'],
			'AR' => ['oldIdUbigeo'=>'4595', 'name'=>'Argentina', 'timeZoneId' => 'America/Argentina/Buenos_Aires', 'asciiName' => 'Argentina', 'code3'=>'ARG','languageId'=>'es','currencyId'=>'ARS'],
			'BO' => ['oldIdUbigeo'=>'4610', 'name'=>'Bolivia', 'timeZoneId' => 'America/La_Paz', 'asciiName' => 'Bolivia', 'code3'=>'BOL','languageId'=>'es','currencyId'=>'BOB'],
			'EC' => ['oldIdUbigeo'=>'4637', 'name'=>'Ecuador', 'timeZoneId' => 'America/Guayaquil', 'asciiName' => 'Ecuador', 'code3'=>'ECU','languageId'=>'es','currencyId'=>'USD'],
			'CR' => ['oldIdUbigeo'=>'4632', 'name'=>'Costa Rica', 'timeZoneId' => 'America/Costa_Rica', 'asciiName' => 'Costa Rica', 'code3'=>'CRI','languageId'=>'es','currencyId'=>'CRC'],
			'MX' => ['oldIdUbigeo'=>'4702', 'name'=>'México', 'timeZoneId' => 'America/Mexico_City', 'asciiName' => 'Mexico', 'code3'=>'MEX','languageId'=>'es','currencyId'=>'MXN'],
			'PA' => ['oldIdUbigeo'=>'4720', 'name'=>'Panamá', 'timeZoneId' => 'America/Panama', 'asciiName' => 'Panama', 'code3'=>'PAN','languageId'=>'es','currencyId'=>'PAB'],
			'PY' => ['oldIdUbigeo'=>'4722', 'name'=>'Paraguay', 'timeZoneId' => 'America/Asuncion', 'asciiName' => 'Paraguay', 'code3'=>'PRY','languageId'=>'es','currencyId'=>'PYG'],
			'UY' => ['oldIdUbigeo'=>'4767', 'name'=>'Uruguay', 'timeZoneId' => 'America/Montevideo', 'asciiName' => 'Uruguay', 'code3'=>'URY','languageId'=>'es','currencyId'=>'UYU'],
			'BR' => ['oldIdUbigeo'=>'4613', 'name'=>'Brasil', 'timeZoneId' => 'America/Sao_Paulo', 'asciiName' => 'Brasil', 'code3'=>'BRA','languageId'=>'pt','currencyId'=>'BRL'],
			'HN' => ['oldIdUbigeo'=>'4664', 'name'=>'Honduras', 'timeZoneId' => 'America/Tegucigalpa', 'asciiName' => 'Honduras', 'code3'=>'HND','languageId'=>'es','currencyId'=>'HNL'],
			'GT' => ['oldIdUbigeo'=>'4658', 'name'=>'Guatemala', 'timeZoneId' => 'America/Guatemala', 'asciiName' => 'Guatemala', 'code3'=>'GTM','languageId'=>'es','currencyId'=>'GTQ'],
			'SV' => ['oldIdUbigeo'=>'4639', 'name'=>'El Salvador', 'timeZoneId' => 'America/El_Salvador', 'asciiName' => 'El Salvador', 'code3'=>'SLV','languageId'=>'es','currencyId'=>'SVC'],
			'VE' => ['oldIdUbigeo'=>'4770', 'name'=>'Venezuela', 'timeZoneId' => 'America/Caracas', 'asciiName' => 'Venezuela', 'code3'=>'VEN','languageId'=>'es','currencyId'=>'VEF'],
			'NI' => ['oldIdUbigeo'=>'4711', 'name'=>'Nicaragua', 'timeZoneId' => 'America/Managua', 'asciiName' => 'Nicaragua', 'code3'=>'NIC','languageId'=>'es','currencyId'=>'NIO'],
			'DO' => ['oldIdUbigeo'=>'4729', 'name'=>'República Dominicana', 'timeZoneId' => 'America/Santo_Domingo', 'asciiName' => 'Republica Dominicana', 'code3'=>'DOM','languageId'=>'es','currencyId'=>'DOP'],
			'PR' => ['oldIdUbigeo'=>'15167', 'name'=>'Puerto Rico', 'timeZoneId' => 'America/Puerto_Rico', 'asciiName' => 'Puerto Rico', 'code3'=>'PRI','languageId'=>'es','currencyId'=>'USD'],
		];
			
		$added = [];
		foreach ($items as $itemId => $item)
		{
			$row = Country::findOne($itemId);
			if (!$row) {
				$row = new Country($item);
				$row->id = $itemId;
				if ($row->save())
					$added[] = $item['name'];
				else 
					Yii::$app->getSession()->addFlash('warning', 'Error al agregar: ' . VarDumper::export($row->errors));
			}
		}
		if (count($added) > 0)
			Yii::$app->getSession()->addFlash('success', 'Se agregaron: ' . implode(", ", $added));
		else
			Yii::$app->getSession()->addFlash('warning', 'Nada que agregar');
			
		return $this->redirect(['index']);
	}
	
	/**
	 * Import all ubigeos from first papaya
	 * 
	 * @param unknown $countryId
	 * @return \yii\web\Response
	 */
	public function actionImportUbigeos($countryId)
	{
		$country = Country::findOne($countryId);
		if (!$country) {
			Yii::$app->getSession()->addFlash('warning', 'No se encontro el pais');
			return $this->redirect(['index']);
		}
		
		if (empty($country->oldIdUbigeo))
		{
			Yii::$app->getSession()->addFlash('warning', 'El pais no tiene ubigeo');
			return $this->redirect(['index']);
		}
		
		// Get old regions
		$oldDb = $this->getOldDb();
		$oldRegionsQry = new \yii\db\Query();
		$oldRegions = $oldRegionsQry->select('*')->from('dp_ubigeo')
			->where('n_parent_ubigeo = ' . $country->oldIdUbigeo)
			->indexBy('n_id_ubigeo')
			->all($oldDb);
		
		// Get existing regions
		$existingRegions = Region::find()->where(['countryId' => $country->id])
			->indexBy('id')->all();
		
		// Add regions only non existent ones
		$regionsAdded = [];
		$regionsFailed = [];
		$regionIdsToAdd = array_diff(array_keys($oldRegions), array_keys($existingRegions));
		foreach ($regionIdsToAdd as $oldRegionId)
		{
			$oldRegion = $oldRegions[$oldRegionId];
			$newRegion = new Region();
			$newRegion->setAttributes([
				'id' => $oldRegionId,
				'name' => $oldRegion['c_name_ubigeo'],
				'countryId' => $country->id,
				'nameSlug' => Inflector::slug($oldRegion['c_name_ubigeo']),
				'asciiName' => str_replace("-", " ", Inflector::slug($oldRegion['c_name_ubigeo'])),
				'fullName' => $oldRegion['c_name_ubigeo'] . ", " . $country->name,
				'asciiFullName' => str_replace("-", " ", Inflector::slug($oldRegion['c_name_ubigeo'])) . ", " . $country->asciiName,
				'timeZoneId' => $country->timeZoneId,
				'latitude' => empty($oldRegion['n_latitude_ubigeo']) ? 0 : $oldRegion['n_latitude_ubigeo'],
				'longitude' => empty($oldRegion['n_longitude_ubigeo']) ? 0 : $oldRegion['n_longitude_ubigeo'],
				'geonameId' => null,
				'oldIdUbigeo' => $oldRegionId,
			], false);
			if (!$newRegion->save())
				$regionsFailed[] = $oldRegionId;
			else
				$regionsAdded[] = $oldRegionId;
		}
		
		// Get existing cities
		$existingCities = City::find()->where(['regionId' => array_keys($oldRegions)])
			->indexBy('id')->all();
		
		// Get cities for all the new regions
		$citiesAdded = [];
		$citiesFailed = [];
		$oldCitiesQry = new \yii\db\Query();
		if (strtolower($countryId) == 'bo')
		{
			$oldCities = $oldCitiesQry->select([
					'n_id_ubigeo' => 'id_ubigeo',
					'c_name_ubigeo' => 'name_ubigeo',
					'n_parent_ubigeo' => 'id_parent_ubigeo',
					'n_latitude_ubigeo' => 'latitude_ubigeo',
					'n_longitude_ubigeo' => 'longitude_ubigeo',
				])->from('ubigeo')
				->where(['id_parent_ubigeo' => array_keys($oldRegions)])
				->indexBy('n_id_ubigeo')
				->all($this->getOldBoliviaDB());
		}
		else 
		{
			$oldCities = $oldCitiesQry->select('*')->from('dp_ubigeo')
				->where(['n_parent_ubigeo' => array_keys($oldRegions)])
				->indexBy('n_id_ubigeo')
				->all($oldDb);
		}
		
		// Add all the cities
		foreach ($oldCities as $oldCityId => $oldCity)
		{
			if (array_key_exists($oldCityId, $existingCities))
				continue;
			$newCity = new City();
			$newCity->setAttributes([
				'id' => $oldCityId,
				'name' => $oldCity['c_name_ubigeo'],
				'regionId' => $oldCity['n_parent_ubigeo'],
				'nameSlug' => Inflector::slug($oldCity['c_name_ubigeo']),
				'asciiName' => str_replace("-", " ", Inflector::slug($oldCity['c_name_ubigeo'])),
				'fullName' => $oldCity['c_name_ubigeo'] . ", " . $country->name,
				'asciiFullName' => str_replace("-", " ", Inflector::slug($oldCity['c_name_ubigeo'])) . ", " . str_replace("-", " ", Inflector::slug($oldRegions[$oldCity['n_parent_ubigeo']]['c_name_ubigeo'])) . ", " . $country->asciiName,
				'timeZoneId' => $country->timeZoneId,
				'latitude' => empty($oldCity['n_latitude_ubigeo']) ? 0 : $oldCity['n_latitude_ubigeo'],
				'longitude' => empty($oldCity['n_longitude_ubigeo']) ? 0 : $oldCity['n_longitude_ubigeo'],
				'geonameId' => null,
				'oldIdUbigeo' => $oldCityId,
			], false);
			if ($newCity->save())
				$citiesAdded[] = $oldCityId;
			else {
				$citiesFailed[] = $oldCityId;
			}
		}
		
		Yii::$app->getSession()->addFlash('success', 'Se agregaron ' . count($regionsAdded) . " regiones");
		Yii::$app->getSession()->addFlash('success', 'Se agregaron ' . count($citiesAdded) . " ciudades");
		if (count($regionsFailed))
			Yii::$app->getSession()->addFlash('error', 'No se agregaron las siguientes regiones: ' . implode(", ", $regionsFailed));
		if (count($citiesFailed))
			Yii::$app->getSession()->addFlash('error', 'No se agregaron las siguientes ciudades: ' . implode(", ", $citiesFailed));
		return $this->redirect(['index']);
	}
	
	/*
	public function actionUpdateGeoRegionFullnames()
	{
		die("DISABLED");
		$db = Region::getDb();
		$affectedRows = $db->createCommand("UPDATE region SET " .
			"region.fullName=CONCAT_WS(', ', region.name, (SELECT country.name FROM country WHERE country.id=countryId)), " .
			"region.asciiFullName=CONCAT_WS(', ', region.asciiName, (SELECT country.asciiName FROM country WHERE country.id=countryId))")
			->execute();
		\Yii::$app->getSession()->addFlash('success', "{$affectedRows} regiones actualizadas");
		return $this->redirect(['index']);
	}
	
	public function actionUpdateGeoCityFullnames()
	{
		die("DISABLED");
		$db = Region::getDb();
		$affectedRows = $db->createCommand("UPDATE city SET " .
			"city.fullName=CONCAT_WS(', ', city.name, (SELECT region.fullName FROM region WHERE region.id=regionId)), " .
			"city.asciiFullName=CONCAT_WS(', ', city.asciiName, (SELECT region.asciiFullName FROM region WHERE region.id=regionId))")
			->execute();
		\Yii::$app->getSession()->addFlash('success', "{$affectedRows} ciudades actualizadas");
		return $this->redirect(['index']);
	}
	
	public function actionMapOldubigeosToGeonames()
	{
		die("DISABLED");
		$country = Country::find()->where(['id' => Yii::$app->request->get('countryId')])->one();
		if (!$country)
		{
			Yii::$app->getSession()->addFlash('error', "Pais invalido");
			return $this->redirect(['index']);
		}
		$oldDb = $this->getOldDb();
		if ($country->id == 'BR')
			$oldCountry = $oldDb->createCommand("SELECT * FROM dp_ubigeo WHERE n_id_ubigeo=4613")->queryOne();
		else
			$oldCountry = $oldDb->createCommand("SELECT * FROM dp_ubigeo WHERE c_code_ubigeo='".strtolower($country->id)."'")->queryOne();
		if (!$oldCountry)
		{
			Yii::$app->getSession()->addFlash('error', "No existe el pais en el antiguo ubigeo");
			return $this->redirect(['index']);
		}
		$oldRegionsQry = new \yii\db\Query();
		$oldRegions = $oldRegionsQry->select('*')->from('dp_ubigeo')
			->where('n_parent_ubigeo = ' . $oldCountry['n_id_ubigeo'])
			->all($oldDb);
		$existingRegions = Region::find()
			->where(['countryId' => $country->id])
			->indexBy('nameSlug')
			->all();
		
		$found = [];
		$forced = [];
		$notFound = [];
		foreach ($oldRegions as $oldRegion)
		{
			$oldSlug = Inflector::slug($oldRegion['c_name_ubigeo'] . " " . $country->id);
			$region = null;
			if (array_key_exists($oldSlug, $existingRegions))
			{
				$region = $existingRegions[$oldSlug];
				if ($oldRegion['n_id_ubigeo'] != $region->oldIdUbigeo)
					$class = 'label-success';
				else
					$class = 'label-default';
				$found[] = "<span class='label {$class}'>" . $oldRegion['n_id_ubigeo'] . ": " . $oldRegion['c_name_ubigeo'] . "</span>";
			}
			else 
			{
				$forcedMap = $this->getForcedMapRegion($country->id, $oldRegion['n_id_ubigeo']);
				if ($forcedMap)
				{
					if (!array_key_exists($forcedMap, $existingRegions))
						$notFound[] = "<span class='label label-danger'>" . $oldRegion['n_id_ubigeo'] . ": " . $oldRegion['c_name_ubigeo'] . ", INVALID MAPPING</span>";
					else
					{
						$region = $existingRegions[$forcedMap];
						if ($oldRegion['n_id_ubigeo'] != $region->oldIdUbigeo)
							$class = 'label-success';
						else
							$class = 'label-default';
						$forced[] = "<span class='label {$class}'>" . $oldRegion['n_id_ubigeo'] . ": " . $oldRegion['c_name_ubigeo'] . " to: {$forcedMap}</span>";
					}
				}
				else
					$notFound[] = "<span class='label label-default'>" . $oldRegion['n_id_ubigeo'] . ": " . $oldRegion['c_name_ubigeo'] . "</span>";
			}
			if ($region !== null)
			{
				if ($oldRegion['n_id_ubigeo'] != $region->oldIdUbigeo)
				{
					$region->oldIdUbigeo = $oldRegion['n_id_ubigeo'];
					if (!$region->save())
					{
						echo "<pre>";
						print_r($region->getErrors());
						echo "</pre>";
						die("Error saving region " . $region->id);
					}
				}
			}
		}
		Yii::$app->getSession()->addFlash('info', "Resultados para map de " . $country->name);
		if (count($found) > 0)
			Yii::$app->getSession()->addFlash('success', "Found: " . implode(" ", $found));
		if (count($forced) > 0)
			Yii::$app->getSession()->addFlash('warning', "Found but forced: " . implode(" ", $forced));
		if (count($notFound) > 0)
			Yii::$app->getSession()->addFlash('error', "Not Found:<br/>" . implode(" ", $notFound));
		return $this->redirect(['index']);
	}
	*/
		/*
	public function actionMapOldubigeosToGeonamesCity()
	{
		die("DISABLED");
		$country = Country::find()->where(['id' => Yii::$app->request->get('countryId')])->one();
		if (!$country)
		{
			Yii::$app->getSession()->addFlash('error', "Pais inválido");
			return $this->redirect(['index']);
		}
		$allRegions = Region::find()->where(['countryId' => $country->id])->andWhere('oldIdUbigeo IS NOT NULL')->all();
		$oldDb = $this->getOldDb();
		$found = [];
		$forced = [];
		$notFound = [];
		foreach ($allRegions as $region)
		{
			//$region = Region::find()->where(['id' => Yii::$app->request->get('regionId')])->one();
			//if (!$region || $region->oldIdUbigeo == null)
			//{
			//	Yii::$app->getSession()->addFlash('error', "Región inválido");
			//	return $this->redirect(['index']);
			//}
			$oldCitiesQry = new \yii\db\Query();
			$oldCities = $oldCitiesQry->select('*')->from('dp_ubigeo')
				->where('n_parent_ubigeo = ' . $region->oldIdUbigeo)
				->all($oldDb);
			
			$existingCities = City::find()
				->where(['regionId' => $region->id])
				->indexBy('nameSlug')
				->all();
			
			$regionFoundAdded = false;
			$regionForcedAdded = false;
			$regionNotFoundAdded = false;
			$added = [];
			$pending = [];
			foreach ($oldCities as $oldCity)
			{
				$oldSlug = Inflector::slug($oldCity['c_name_ubigeo']) . "-" . $region->nameSlug;
				
				$city = null;
				if (array_key_exists($oldSlug, $existingCities))
				{
					if (array_key_exists($oldSlug, $added))
						continue;
					$city = $existingCities[$oldSlug];
					$added[$oldSlug] = $city;
					if ($oldCity['n_id_ubigeo'] != $city->oldIdUbigeo)
						$class = 'label-success';
					else
						$class = 'label-default';
					if (!$regionFoundAdded) {
						$regionFoundAdded = true;
						$found[] = "<span class='label label-info'>{$region->id} - " . $region->name . "</span>";
					}
					$found[] = "<span class='label {$class}'>{$region->id} - " . $oldCity['n_id_ubigeo'] . ": " . $oldCity['c_name_ubigeo'] . "</span>";
					if ($oldCity['n_id_ubigeo'] != $city->oldIdUbigeo)
					{
						$city->oldIdUbigeo = $oldCity['n_id_ubigeo'];
						if (!$city->save())
							die("Error saving region " . $city->id);
					}
				}
				else 
					$pending[$oldCity['n_id_ubigeo']] = $oldCity;
			}
			foreach ($pending as $oldCity)
			{
				$oldSlug = Inflector::slug($oldCity['c_name_ubigeo']);
				$city = null;
				
				$forcedMap = $this->getForcedMapCity($region->countryId, $region->id, $oldCity['n_id_ubigeo'], $oldSlug, $existingCities, $oldCities);
				if ($forcedMap && array_key_exists($forcedMap, $added))
				{
					$forcedMap = null;
				}
				if ($forcedMap)
				{
					if (!array_key_exists($forcedMap, $existingCities))
					{
						if (!$regionNotFoundAdded) {
							$regionNotFoundAdded = true;
							$notFound[] = "<span class='label label-info'>{$region->id} - " . $region->name . "</span>";
						}
						$notFound[] = "<span class='label label-danger'>{$region->id} - " . $oldCity['n_id_ubigeo'] . ": " . $oldCity['c_name_ubigeo'] . ", INVALID MAPPING</span>";
					}
					else
					{
						$city = $existingCities[$forcedMap];
						$added[$forcedMap] = $city;
						if ($oldCity['n_id_ubigeo'] != $city->oldIdUbigeo)
							$class = 'label-success';
						else
							$class = 'label-default';
						if (!$regionForcedAdded) {
							$regionForcedAdded = true;
							$forced[] = "<span class='label label-info'>{$region->id} - " . $region->name . "</span>";
						}
						$forced[] = "<span class='label {$class}'>{$region->id} - " . $oldCity['n_id_ubigeo'] . ": " . $oldCity['c_name_ubigeo'] . " to: {$forcedMap}</span>";
					}
				}
				else {
					if (!$regionNotFoundAdded) {
						$regionNotFoundAdded = true;
						$notFound[] = "<span class='label label-info'>{$region->id} - " . $region->name . "</span>";
					}
					$notFound[] = "<span class='label label-default'>{$region->id} - " . $oldCity['n_id_ubigeo'] . ": " . $oldCity['c_name_ubigeo'] . "</span>";
				}
				if ($city !== null)
				{
					if ($oldCity['n_id_ubigeo'] != $city->oldIdUbigeo)
					{
						$city->oldIdUbigeo = $oldCity['n_id_ubigeo'];
						if (!$city->save())
							die("Error saving region " . $city->id);
					}
				}
			}
		}
		Yii::$app->getSession()->addFlash('info', "Resultados para map de ciudades en " . $country->name);
		if (count($found) > 0)
			Yii::$app->getSession()->addFlash('success', "Found: " . implode(" ", $found));
		if (count($forced) > 0)
			Yii::$app->getSession()->addFlash('warning', "Found but forced: " . implode(" ", $forced));
		if (count($notFound) > 0)
			Yii::$app->getSession()->addFlash('error', "Not Found:<br/>" . implode(" ", $notFound));
		return $this->redirect(['index']);
	}
	
	public function getForcedMapRegion($countryId, $oldIdUbigeo)
	{
		die("DISABLED");
		$mapping = [
			'AR' => [
				13565 => 'provincia-del-neuquen',
				13554 => 'provincia-del-chaco',
				13555 => 'provincia-del-chubut',
				13573 => 'tierra-del-fuego-antartida-e-islas-del-atlantico-sur',
			],
			'BO' => [
				13431 => 'el-beni',
				13434 => 'santa-cruz-2',
			],
			'BR' => [
				13801 => 'federal-district',
			],
			'CL' => [
				13108 => 'region-del-libertador-general-bernardo-ohiggins',
				13145 => 'maule',
				13239 => 'la-araucania',
				13274 => 'los-lagos',
				13309 => 'aysen',
				13324 => 'magallanes-y-de-la-antartica-chilena',
				13399 => 'los-rios',
			],
			'CO' => [
				10691 => 'amazonas-3',
				10980 => 'providencia-y-santa-catalina-archipielago-de-san-andres',
				11696 => 'cordoba-2',
				12185 => 'meta',
				12481 => 'quindio-department',
				12965 => 'distrito-capital-de-bogota',
			],
			'CR' => [
				13702 => 'san-jose-2',
			],
			'EC' => [
				5241 => 'bolivar-2',
				5242 => 'canar',
				5251 => 'los-rios-2',
				5261 => 'francisco-de-orellana',
			],
			'MX' => [
				6530 => 'coahuila-de-zaragoza',
				6534 => 'mexico',
				6539 => 'michoacan-de-ocampo',
				6545 => 'queretaro-de-arteaga',
				6553 => 'veracruz-llave',
			],
			'PA' => [
				13680 => 'provincia-del-darien',
				13688 => 'ngobe-bugle',
			],
			'PE' => [
				2534 => 'amazonas-2',
			],
			'PY' => [
				13647 => 'misiones-2',
				13650 => 'departamento-central',
			],
			'UY' => [
				13618 => 'rio-negro-2',
			],
		];
		if (!array_key_exists($countryId, $mapping))
			return null;
		if (!array_key_exists($oldIdUbigeo, $mapping[$countryId]))
			return null;
		return $mapping[$countryId][$oldIdUbigeo] . "-" . strtolower($countryId);
	}
	
	public function getForcedMapCity($countryId, $regionId, $oldIdUbigeo, $citySlug, $existingCities, $oldCities)
	{
		die("DISABLED");
		if (count($oldCities) == 1)
			return key($existingCities);
		$mapping = [
		];
		$res = null;
		if (array_key_exists($countryId, $mapping) && 
			array_key_exists($regionId, $mapping[$countryId]) && 
			array_key_exists($oldIdUbigeo, $mapping[$countryId][$regionId]))
		{
			$res = $mapping[$countryId][$regionId][$oldIdUbigeo];
		}	

		if ($res == null)
		{
			$bestResult = null;
			$bestResultMatch = 0;
			foreach (array_keys($existingCities) as $newCitySlug)
			{
				$percent = $this->wordmatch($citySlug, $newCitySlug);
				if ($percent > 0)
				{
					if ($bestResult == null)
					{
						$bestResult = $newCitySlug;
						$bestResultMatch = $percent;
					}
					else if ($percent > $bestResultMatch)
					{
						$bestResult = $newCitySlug;
						$bestResultMatch = $percent;
					}
				}
			}
			$res = $bestResult;
		}
		return $res;
	}
	
	public function wordmatch($first, $second)
	{
		die("DISABLED");
		$words1 = explode("-", $first);
		$words2 = explode("-", $second);
		$matchCount = 0;
		$lettersMatched = 0;
		foreach ($words1 as $word1)
		{
			if (in_array($word1, $words2))
			{
				$matchCount++;
				$lettersMatched += strlen($word1);
			}
		}
		if ($matchCount == 0)
			return 0;
		return $lettersMatched / (strlen($second) * 100);
	}*/
}