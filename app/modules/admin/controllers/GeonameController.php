<?php
namespace modules\admin\controllers;

use Yii;
use yii\web\Controller;
use orm\admin\Country;
use yii\web\UploadedFile;
use yii\base\Model;
use modules\admin\models\UploadForm;
use yii\web\NotFoundHttpException;
use common\yii\helpers\StringHelper;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use common\yii\helpers\ArrayHelper;
use orm\admin\Region;
use orm\admin\City;

class GeonameController extends Controller
{
	protected function getCountry()
	{
		$country = Country::findOne(['id' => Yii::$app->request->get('id', 'null')]);
		if (!$country)
			throw new NotFoundHttpException("Pais invalido");
		return $country;
	}
	
	protected function getGeoNameZipFileFullName($country)
	{
		return Yii::$app->runtimePath . "/geonames/{$country->id}.zip";
	}
	
	protected function getGeoNameTxtFileFullName($country)
	{
		return StringHelper::dirname($this->getGeoNameZipFileFullName($country)) . "/" . $country->id . "/" . $country->id . ".txt";
	}
	
	protected function getGeoNamePhpFileFullName($country)
	{
		return StringHelper::dirname($this->getGeoNameZipFileFullName($country)) . "/" . $country->id . "/" . $country->id . ".php";
	}
	
	public function actionIndex()
	{
		$country = $this->getCountry();
		$filename = $this->getGeoNameZipFileFullName($country);
		$fileExists = false;
		$fileLastModif = null;
		if (file_exists($filename))
		{
			$fileExists = true;
			$fileLastModif = date('Y-m-d H:i:s', filemtime($filename));
		}
		
		return $this->render('index', [
			'country' => $country,
			'fileExists' => $fileExists,
			'fileLastModif' => $fileLastModif,
		]);
	}
	
	public function actionDownload()
	{
		$country = $this->getCountry();
		$filename = $this->getGeoNameZipFileFullName($country); 
		FileHelper::createDirectory(StringHelper::dirname($filename));
		set_time_limit(300);
		if (file_exists($filename))
			@unlink($filename);
		file_put_contents($filename, fopen("http://download.geonames.org/export/dump/{$country->id}.zip", 'r'));
		echo json_encode(['ok'=>file_exists($filename)]);
		die();
	}
	
	public function actionParse()
	{
		set_time_limit(300);
		$country = $this->getCountry();
		$filename = $this->getGeoNameZipFileFullName($country);
		if (!file_exists($filename))
		{
			echo json_encode(['ok'=>false, 'error' => 'File does not exist']);
			die();
		}
		$zip = new \ZipArchive();
		if (!$zip->open($filename)) {
			echo json_encode(['ok'=>false, 'error' => 'Could not unzip']);
			die();
		}
		
		// Unzip file
		$txtFilename = $this->getGeoNameTxtFileFullName($country);
		if (file_exists($txtFilename))
			@unlink($txtFilename);
		$zip->extractTo(StringHelper::dirname($txtFilename));
		$zip->close();
		
		// Check CC.txt file exists
		if (!file_exists($txtFilename))
		{
			echo json_encode(['ok'=>false, 'error' => 'Expected file does not exist inside zip']);
			die();
		}
		
		// Parse to extract regions and cities
		$geo = $this->_parseGeoNameFile($txtFilename);
		
		// Save regions in a temp php file
		$phpFilename = $this->getGeoNamePhpFileFullName($country);
		$phpContent = "<?php\nreturn " . ArrayHelper::toStringPhpRepresentation($geo) . ";\n";
		if (file_exists($phpFilename))
			unlink($phpFilename);
		file_put_contents($phpFilename, $phpContent);
		
		// Calculate differences
		$diff = $this->getDifferences($country, true);
		
		// Build table of changes
		$html = "<table class='table table-bordered' style='width:100%;'>";
		$html .= "<tr><th style='width:10px;'>STATE</th><th style='width:10px;'>TYPE</th><th>SOURCE</th><th>CURRENT</th><th style='width:10px;'>ACTION</th></tr>";
		foreach ($diff['errors'] as $error)
			$html .= "<tr class='danger'><td class='text-danger'>ERROR</td><td colspan='4' class='text-danger'><strong>" . $error . "</strong></td></tr>";
		foreach ($diff['insert'] as $insertId => $insert)
		{
			$html .= "<tr class='success'>";
			$html .= "<td>INSERT</td>";
			$html .= "<td>REGION</td>";
			$html .= "<td>{$insert['name']}</td>";
			$html .= "<td>&nbsp;</td>";
			$html .= "<td class='text-center'>&nbsp;</td>";
			$html .= "</tr>";
		}
		foreach ($diff['insertC'] as $insertId => $insert)
		{
			$html .= "<tr class='success'>";
			$html .= "<td>INSERT</td>";
			$html .= "<td>CITY</td>";
			$html .= "<td>{$insert['name']}</td>";
			$html .= "<td>&nbsp;</td>";
			$html .= "<td class='text-center'>&nbsp;</td>";
			$html .= "</tr>";
		}
		foreach ($diff['update'] as $updateId => $update)
		{
			$html .= "<tr class='warning'>";
			$html .= "<td>UPDATE</td>";
			$html .= "<td>REGION</td>";
			$html .= "<td><pre>". VarDumper::export($update['geo']). "</pre></td>";
			$html .= "<td><pre>". VarDumper::export($update['db']). "</pre></td>";
			$html .= "<td class='text-center'><input type='checkbox' name='update[{$updateId}]'/></td>";
			$html .= "</tr>";
		}
		foreach ($diff['updateC'] as $updateId => $update)
		{
			$html .= "<tr class='warning'>";
			$html .= "<td>UPDATE</td>";
			$html .= "<td>CITY</td>";
			$html .= "<td><pre>". VarDumper::export($update['geo']). "</pre></td>";
			$html .= "<td><pre>". VarDumper::export($update['db']). "</pre></td>";
			$html .= "<td class='text-center'><input type='checkbox' name='updateC[{$updateId}]'/></td>";
			$html .= "</tr>";
		}
		foreach ($diff['delete'] as $deleteId => $delete)
		{
			$html .= "<tr class='danger'>";
			$html .= "<td>DELETE</td>";
			$html .= "<td>REGION</td>";
			$html .= "<td>{$delete['name']}</td>";
			$html .= "<td>&nbsp;</td>";
			$html .= "<td>&nbsp;</td>";
			$html .= "</tr>";
		}
		foreach ($diff['deleteC'] as $deleteId => $delete)
		{
			$html .= "<tr class='danger'>";
			$html .= "<td>DELETE</td>";
			$html .= "<td>CITY</td>";
			$html .= "<td>{$delete['name']}</td>";
			$html .= "<td>&nbsp;</td>";
			$html .= "<td>&nbsp;</td>";
			$html .= "</tr>";
		}
		$html .= "</table>";
		
		echo json_encode([
			'ok'=>true, 
			'result' => $html,
		]);
		die();
	}
	
	public function actionApply()
	{
		set_time_limit(300);
		$country = $this->getCountry();
		$phpFilename = $this->getGeoNamePhpFileFullName($country);
		if (!file_exists($phpFilename))
		{
			echo json_encode([
				'ok'=>false,
				'error' => "Not parsed",
			]);
			die();
		}
		
		// Calculate differences
		$diff = $this->getDifferences($country, false);
		
		$insertPost = $diff['insert'];
		$updatePost = Yii::$app->request->post('update', []);
		$insertCPost = $diff['insertC'];
		$updateCPost = Yii::$app->request->post('updateC', []);
		foreach ($insertPost as $insertId => $on)
		{
			$row = new Region([
				'name' => $diff['insert'][$insertId]['name'],
				'countryId' => $country->id,
				'asciiName' => $diff['insert'][$insertId]['ascii'],
				'fullName' => $diff['insert'][$insertId]['name'] . ", " . $country->name,
				'asciiFullName' => $diff['insert'][$insertId]['ascii'] . ", " . $country->asciiName,
				'timeZoneId' => $diff['insert'][$insertId]['timezone'],
				'latitude' => $diff['insert'][$insertId]['lat'],
				'longitude' => $diff['insert'][$insertId]['lon'],
			]);
			$row->id = $insertId;
			$row->save();
		}
		foreach ($updatePost as $updateId => $on)
		{
			$row = $diff['update'][$updateId]['db'];
			$row->setAttributes([
				'name' => $diff['update'][$updateId]['geo']['name'],
				'asciiName' => $diff['update'][$updateId]['geo']['ascii'],
				'fullName' => $diff['update'][$updateId]['geo']['name'] . ", " . $country->name,
				'asciiFullName' => $diff['update'][$updateId]['geo']['ascii'] . ", " . $country->asciiName,
				'timeZoneId' => $diff['update'][$updateId]['geo']['timezone'],
				'latitude' => $diff['update'][$updateId]['geo']['lat'],
				'longitude' => $diff['update'][$updateId]['geo']['lon'],
			]);
			$row->save();
		}
		foreach ($insertCPost as $insertId => $on)
		{
			$row = new City([
				'name' => $diff['insertC'][$insertId]['name'],
				'regionId' => $diff['insertC'][$insertId]['regionId'],
				'asciiName' => $diff['insertC'][$insertId]['ascii'],
				'fullName' => $diff['insertC'][$insertId]['name'],
				'asciiFullName' => $diff['insertC'][$insertId]['ascii'],
				'timeZoneId' => $diff['insertC'][$insertId]['timezone'],
				'latitude' => $diff['insertC'][$insertId]['lat'],
				'longitude' => $diff['insertC'][$insertId]['lon'],
				'isMainCity' => $diff['insertC'][$insertId]['capital'] ? true : false,
			]);
			$row->id = $insertId;
			$row->save();
		}
		foreach ($updateCPost as $updateId => $on)
		{
			$row = $diff['updateC'][$updateId]['db'];
			$row->setAttributes([
				'name' => $diff['updateC'][$updateId]['geo']['name'],
				'asciiName' => $diff['updateC'][$updateId]['geo']['ascii'],
				'fullName' => $diff['updateC'][$updateId]['geo']['name'],
				'asciiFullName' => $diff['updateC'][$updateId]['geo']['ascii'],
				'timeZoneId' => $diff['updateC'][$updateId]['geo']['timezone'],
				'latitude' => $diff['updateC'][$updateId]['geo']['lat'],
				'longitude' => $diff['updateC'][$updateId]['geo']['lon'],
				'isMainCity' => $diff['updateC'][$updateId]['geo']['capital'] ? true : false,
			]);
			$row->save();
		}
		
		echo json_encode([
			'ok'=>true,
		]);
		die();
	}
	
	protected function getDifferences($country, $asArray = false)
	{
		$geo = include($this->getGeoNamePhpFileFullName($country));
		$geoRegions = $geo['regions'];
		$errors = [];
		$insert = [];
		$delete = [];
		$update = [];
		$insertC = [];
		$deleteC = [];
		$updateC = [];
		
		// Add all as inserts
		foreach ($geoRegions as $geoRegionId => $geoRegionInfo)
		{
			$insert[$geoRegionId] = $geoRegionInfo; 
		}
		
		// Find all regions and detect updates and deletes
		$qryRegions = Region::find()
			->where(['countryId' => $country->id])
			->indexBy('id');
		foreach ($qryRegions->batch(100) as $regions)
		{
			foreach ($regions as $regionId => $region)
			{
				// If in db then it is not an insert
				if (array_key_exists($regionId, $insert))
				{
					foreach ($insert[$regionId]['cities'] as $cityId => $cityInfo)
					{
						$insertC[$cityId] = $cityInfo;
					}
					unset($insert[$regionId]);
				}
				
				// Not in geonames then delete from db
				if (!array_key_exists($regionId, $geoRegions))
				{
					$delete[$regionId] = $asArray ? $region->toArray() : $region;
					continue;
				} 
				
				// Update if changed
				$regionInfo = $geoRegions[$regionId];
				if ($region->name != $geoRegions[$regionId]['name'] ||
					$region->asciiName != $geoRegions[$regionId]['ascii'] ||
					$region->latitude != $geoRegions[$regionId]['lat'] ||
					$region->longitude != $geoRegions[$regionId]['lon'] ||
					$region->timeZoneId != $geoRegions[$regionId]['timezone'])
				{
					unset($regionInfo['cities']);
					$update[$regionId] = [
						'db' => $asArray ? $region->toArray() : $region,
						'geo' => $regionInfo,
					];
				}
				
				// Find cities
				$qryCities = City::find()->where([
					'regionId' => $regionId,
				])->indexBy('id');
				foreach ($qryCities->batch(100) as $cities)
				{
					foreach ($cities as $cityId => $city)
					{
						if (array_key_exists($cityId, $insertC))
							unset($insertC[$cityId]);
						
						if (!array_key_exists($cityId, $geoRegions[$regionId]['cities']))
						{
							$deleteC[$cityId] = $asArray ? $city->toArray() : $city;
							continue;
						}
						$cityInfo = $geoRegions[$regionId]['cities'][$cityId];
						if ($city->name != $cityInfo['name'] ||
							$city->asciiName != $cityInfo['ascii'] ||
							$city->latitude != $cityInfo['lat'] ||
							$city->longitude != $cityInfo['lon'] ||
							$city->timeZoneId != $cityInfo['timezone'])
						{
							$updateC[$cityId] = [
								'db' => $asArray ? $city->toArray() : $city,
								'geo' => $cityInfo,
							];
						}
					}
				}
			}
		}
		
		// Split all cities of regions to insert
		foreach ($insert as $regionId => $regionInfo)
		{
			foreach ($regionInfo['cities'] as $cityId => $cityInfo)
			{
				$insertC[$cityId] = $cityInfo;
			}
			unset($insert[$regionId]['cities']);
		}
		
		return [
			'errors' => $geo['errors'],
			'insert' => $insert,
			'update' => $update,
			'delete' => $delete,
			'insertC' => $insertC,
			'updateC' => $updateC,
			'deleteC' => $deleteC,
		];
	}
	
	protected function _parseGeoNameFile($txtFilename)
	{
		$errors = [];
		$regions = [];
		$cities = [];
		$regionsByKey = [];
		$capital = null;
		$tz = [];
		
		$f = fopen($txtFilename, "rt");
		$rows = 0;
		while(!feof($f))
		{
			$line = fgets($f);
			$geoInfo = $this->_rowToGeoInfo($line);
			if (!$geoInfo || $geoInfo['fClass'] != 'A') {
				if ($geoInfo && $geoInfo['fClass'] == 'P' && $geoInfo['fCode'] == 'PPLC')
				{
					if ($capital !== null)
						$errors[] = "Duplicate capital";
					$capital = $geoInfo;
				}
				continue;
			}
			$admin1 = "K" . $geoInfo['admin1'];
			$admin2 = "K" . $geoInfo['admin2'];
			if (strtoupper($geoInfo['fCode']) == 'ADM1')
			{
				if (array_key_exists($geoInfo['id'], $regions))
					$errors[] = "Duplicate region id {$geoInfo['id']}";
				if (array_key_exists($admin1, $regionsByKey))
					$errors[] = "Duplicate region admin1 id {$admin1}";
				$regions[$geoInfo['id']] = $geoInfo;
				$regions[$geoInfo['id']]['cities'] = [];
				$regionNames[$geoInfo['id']] = $geoInfo['name'];
				$regionsByKey[$admin1] = $geoInfo['id'];
			}
			if (strtoupper($geoInfo['fCode']) == 'ADM2')
			{
				if (array_key_exists($geoInfo['id'], $cities))
					$errors[] = "Duplicate city id {$geoInfo['id']}";
				$cities[$geoInfo['id']] = $geoInfo;
				$cityNames[$geoInfo['id']] = $geoInfo['name'];
			}
		}
		fclose($f);
		
		// Add cities to it's corresponding parent
		foreach ($cities as $geoId => $geoInfo)
		{
			$admin1 = "K" . $geoInfo['admin1'];
			$admin2 = "K" . $geoInfo['admin2'];
			if (!array_key_exists($admin1, $regionsByKey))
				$errors[] = "Invalid region key {$admin1}";
			else 
			{
				$regionGeoId = $regionsByKey[$admin1];
				$regions[$regionGeoId]['cities'][$geoInfo['id']] = $geoInfo;
				$regions[$regionGeoId]['cities'][$geoInfo['id']]['regionId'] = $regionGeoId;
				$regions[$regionGeoId]['cities'][$geoInfo['id']]['capital'] = 0;
				if ($capital !== null && $capital['ascii'] == $geoInfo['ascii'])
					$regions[$regionGeoId]['cities'][$geoInfo['id']]['capital'] = 1;
			}
		}
		
		// Discard regions that have no cities
		foreach ($regions as $regionId => $regionInfo)
		{
			if (count($regionInfo['cities']) == 0)
			{
				if ($regionInfo['country'] == 'PY' || $regionInfo['country'] == 'UY')
				{
					$regions[$regionId]['cities'][$regionInfo['id'] . "0099"] = [
						'id' => $regionInfo['id'] . "0099",
						'regionId' => $regionId,
						'name' => $regionInfo['name'],
						'ascii' => $regionInfo['ascii'],
						'lat' => $regionInfo['lat'],
						'lon' => $regionInfo['lon'],
						'fClass' => $regionInfo['fClass'],
						'fCode' => 'ADM2',
						'country' => $regionInfo['country'],
						'admin1' => $regionInfo['admin1'],
						'admin2' => $regionInfo['admin1'] . "0099",
						'timezone' => $regionInfo['timezone'],
						'capital' => false,
					];
				}
				else 
					unset($regions[$regionId]);
			}
		}
		
		return [
			'errors' => $errors,
			'regions' => $regions,
		];
	}
	
	protected function _rowToGeoInfo($line)
	{
		$info = explode("\t", $line);
		if (count($info) < 18) {
			return null;
		}
		$geoInfo = [
			'id' => $info[0],
			'name' => $info[1],
			'ascii' => $info[2],
			//'alt' => $info[3],
			'lat' => doubleval($info[4]),
			'lon' => doubleval($info[5]),
			'fClass' => $info[6],
			'fCode' => $info[7],
			'country' => $info[8],
			//'cc2' => $info[9],
			'admin1' => $info[10],
			'admin2' => $info[11],
			//'admin3' => $info[12],
			//'admin4' => $info[13],
			//'pop' => $info[14],
			//'elev' => $info[15],
			//'dem' => $info[16],
			'timezone' => $info[17],
			//'modif' => $info[18],
		];
		
		if ($geoInfo['country'] == 'PE' || $geoInfo['country'] == 'CL')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Región de ", "", $geoInfo['name']);
				$geoInfo['name'] = str_replace("Departamento de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Region de ", "", $geoInfo['ascii']);
				$geoInfo['ascii'] = str_replace("Departamento de ", "", $geoInfo['ascii']);
			}
			if ($geoInfo['fCode'] == 'ADM2')
			{
				$geoInfo['name'] = str_replace("Provincia de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia de ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'AR')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Provincia de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia de ", "", $geoInfo['ascii']);
			}
			if ($geoInfo['fCode'] == 'ADM2')
			{
				$geoInfo['name'] = str_replace("Partido de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Partido de ", "", $geoInfo['ascii']);
				$geoInfo['name'] = str_replace("Departamento de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento de ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'BO')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Departamento de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento de ", "", $geoInfo['ascii']);
			}
			if ($geoInfo['fCode'] == 'ADM2')
			{
				$geoInfo['name'] = str_replace("Provincia ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'CO')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Departamento de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento de ", "", $geoInfo['ascii']);
				$geoInfo['name'] = str_replace("Departamento del ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento del ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'CR')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Provincia de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia de ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'EC')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Provincia de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia de ", "", $geoInfo['ascii']);
				$geoInfo['name'] = str_replace("Provincia del ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia del ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'MX')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Estado de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Estado de ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'PA')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Provincia de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia de ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'PY')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Departamento de la ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento de la ", "", $geoInfo['ascii']);
				$geoInfo['name'] = str_replace("Departamento de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento de ", "", $geoInfo['ascii']);
				$geoInfo['name'] = str_replace("Departamento del ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento del ", "", $geoInfo['ascii']);
			}
			if ($geoInfo['fCode'] == 'ADM2')
			{
				$geoInfo['name'] = str_replace("Provincia ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia ", "", $geoInfo['ascii']);
			}
		}
		if ($geoInfo['country'] == 'UY')
		{
			if ($geoInfo['fCode'] == 'ADM1')
			{
				$geoInfo['name'] = str_replace("Departamento de ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Departamento de ", "", $geoInfo['ascii']);
			}
			if ($geoInfo['fCode'] == 'ADM2')
			{
				$geoInfo['name'] = str_replace("Provincia ", "", $geoInfo['name']);
				$geoInfo['ascii'] = str_replace("Provincia ", "", $geoInfo['ascii']);
			}
		}
		return $geoInfo;
	}
}