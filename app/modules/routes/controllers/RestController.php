<?php
namespace modules\routes\controllers;

use orm\bus\Route;
use orm\bus\Bus;
use orm\bus\SmartNode;
use yii\rest\Controller;
use yii\web\HttpException;

class RestController extends Controller
{
	public function actionIndex($pos, $id = null)
	{
		$arrayPos = explode("|", $pos);
		array_pop($arrayPos);

		$position = [];
		foreach($arrayPos as $p)
		{
			$arrayP = explode(";", $p);
			$position[] = [
				'latitude' => $arrayP[0],
				'longitude' => $arrayP[1],
			];
		}

		$km = 0.6;

		$lines = [];
		$i = 0;
		$puntos = [];
		$stops = [];
		foreach($position as $p)
		{
			$sql = "
              SELECT 
                  lineId,
                  latitude,
                  longitude,
                  ( 6371 * acos( cos( radians(".$p['latitude'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$p['longitude'].") ) + sin( radians(".$p['latitude'].") ) * sin( radians( latitude ) ) ) ) AS distance 
              FROM 
                route
              HAVING 
                distance < '".$km."'
              ORDER BY 
                distance ";

			$pp = \Yii::$app->db->createCommand($sql)->queryAll();
			$stops[] = $pp;

			foreach( $pp as $posi)
			{
				$puntos[$i][] = md5($posi['latitude'].$posi['longitude']);
				$lines[$posi['lineId']] = 'nada';
			}
			$i++;
		}

		if(empty($stops) or count($stops) != 2)
		{
			throw new HttpException('400', 'No se encontraron paraderos cercanos');
		}

		$routes = Route::find()->where(['lineId' => array_keys($lines)])->asArray()->all();
		$routesByLine = [];
		$routesByRoute = [];
		foreach ( $routes as $r )
		{
			$latLon = md5($r['latitude'].$r['longitude']);

			$routesByLine[$r['lineId']][$latLon] = [
				'latitude' => $r['latitude'],
				'longitude' => $r['longitude'],
				'name' => $r['name'],
			];

			$routesByRoute[$latLon][$r['lineId']] = null;
		}

		$buses = [];
		$flag = true;
		$startPoint = [];
		foreach ( $stops[0] as $stop )
		{
			$latLon = md5($stop['latitude'].$stop['longitude']);
			$startPoint[$latLon] = null;

			$bus = array_keys($routesByRoute[$latLon]);
			foreach ( $bus as $b )
			{
				$buses[$b] = null;
			}

		}

		$oneBus = [];
		$endPoint = [];
		foreach ( $stops[1] as $stop )
		{
			$latLon = md5($stop['latitude'].$stop['longitude']);
			$endPoint[$latLon] = null;

			foreach ( array_keys($buses) as $k )
			{
				$b = $routesByLine[$k];

				if( array_key_exists($latLon, $b) )
				{
					$oneBus[$k] = null;
				}
			}
		}

		$buses2 = $buses;
		$buses = [];
		foreach ( array_keys($buses2) as $k)
		{
			if( ! array_key_exists($k, $oneBus) ) {
				$buses[$k] = null;
			}
		}

		$twoBus = [];
		foreach ( array_keys($buses) as $bus)
		{
			$route = $routesByLine[$bus];

			foreach( $route as $r => $data)
			{
				$linesR = $routesByRoute[$r];
				foreach( array_keys($linesR) as $lr)
				{
					if($lr != $bus and !array_key_exists($lr, $oneBus))
					{
						$rr = $routesByLine[$lr];
						foreach ( array_keys($endPoint) as $eP)
						{
							if(array_key_exists($eP, $rr))
							{
								$twoBus[$bus.$lr] = [
									$bus,
									$lr,
									$r
								];
							}
						}
					}
				}

			}
		}
		/*$a = $twoBus[110299];
		$twoBus = [];
		$twoBus[110299] = $a;*/

		$route = [];
		foreach ( $twoBus as $bus )
		{
			$k =$bus[0].'-'.$bus[1];
			$busOne = $routesByLine[$bus[0]];
			$busTwo = $routesByLine[$bus[1]];

			$flag = false;
			$last = null;
			$route[$k]['distance'] = 0;
			foreach($busOne as $bus1)
			{
				$key = md5($bus1['latitude'].$bus1['longitude']);
				if(array_key_exists($key, $startPoint))
				{
					$flag = true;
				}

				if($flag)
				{
					$route[$k]['route'][0][] = $bus1;

					if(empty($last))
					{
						$last = $bus1;
					}
					else
					{
						$lat1 = $last['latitude'];
						$lon1 = $last['longitude'];
						$lat2 = $bus1['latitude'];
						$lon2 = $bus1['longitude'];
						$route[$k]['distance']+= $this->distance($lat1, $lon1, $lat2, $lon2);
						$last = $bus1;
					}
				}

				if($key == $bus[2])
				{
					break;
				}
			}

			$flag = false;
			$last = null;
			foreach($busTwo as $bus2)
			{
				$key = md5($bus2['latitude'].$bus2['longitude']);

				if($key == $bus[2])
				{
					$flag = true;
				}

				if($flag)
				{
					$route[$k]['route'][1][] = $bus2;

					if(empty($last))
					{
						$last = $bus2;
					}
					else
					{
						$lat1 = $last['latitude'];
						$lon1 = $last['longitude'];
						$lat2 = $bus2['latitude'];
						$lon2 = $bus2['longitude'];
						$route[$k]['distance']+= $this->distance($lat1, $lon1, $lat2, $lon2);
						$last = $bus2;
					}
				}

				if(array_key_exists($key, $endPoint))
				{
					break;
				}
			}
		}

		$realRoute = [];
		foreach( $route as $k => $r)
		{
			if(isset($r['route'][0]) and isset($r['route'][1]))
			{
				$realRoute[$k] = $r;
			}
		}
		$route = [];
		$route = $realRoute;



		uasort($route, function($a, $b){
			return $a['distance'] > $b['distance'];
		});

		$bestRoutes = [];
		$i = 0;
		foreach($route as $k => $v)
		{
			if(array_key_exists($k, $route)) {
				$bestRoutes[$k] = $route[$k];
				$i++;
				if ($i > 10)
					break;
			}
		}

		$route = [];
		foreach( array_keys($oneBus) as $bus)
		{
			$routes = $routesByLine[$bus];

			$flag = false;
			$route[$bus]['distance'] = 0;
			foreach($routes as $r)
			{
				$key = md5($r['latitude'].$r['longitude']);

				if(array_key_exists($key, $startPoint))
				{
					$flag = true;
				}

				if($flag)
				{
					$route[$bus]['route'][0][] = $r;

					if(empty($last))
					{
						$last = $r;
					}
					else
					{
						$lat1 = $last['latitude'];
						$lon1 = $last['longitude'];
						$lat2 = $r['latitude'];
						$lon2 = $r['longitude'];
						$route[$bus]['distance']+= $this->distance($lat1, $lon1, $lat2, $lon2);
						$last = $r;
					}
				}

				if(array_key_exists($key, $endPoint))
				{
					break;
				}
			}
		}

		$realRoute = [];
		$buses = [];
		foreach( $route as $k => $r)
		{
			if(isset($r['route']))
			{
				$bestRoutes[$k] = $r;
			}
		}

		$buses = [];
		foreach($bestRoutes as $k => $bR)
		{
			$bb = explode("-", $k);
			if(isset($bb[1]))
			{
				$buses[] = $bb[0];
				$buses[] = $bb[1];
			}
			else
			{
				$buses[] = $bb[0];
			}
		}

		$busesM = Bus::find()->select(['idLine', 'name', 'extraInfo'])->where(['idLine' => $buses])->indexBy('idLine')->asArray()->all();
		foreach($busesM as $k => $v)
		{
			$busesM[$k]['extraInfo'] = json_decode($busesM[$k]['extraInfo'], true);
		}

		uasort($bestRoutes, function($a, $b){
			return $a['distance'] > $b['distance'];
		});

		$finalRoute = [];
		foreach($bestRoutes as $k => $bR)
		{
			$buses = [];
			$bb = explode("-", $k);
			if(isset($bb[1]))
			{
				$buses[] = $busesM[$bb[0]];
				$buses[] = $busesM[$bb[1]];
			}
			else
			{
				$buses[] = $busesM[$bb[0]];
			}
			$finalRoute[] = [
				'key' => $k,
				'buses' => $buses,
				'data'  => [
					'distance'      => round($bR['distance'], 2),
					'realDistance'  => $bR['distance'],
					'route'         => $bR['route'],
				],
			];
		}

		if( ! empty($id))
		{
			$ruta = $bestRoutes[$id];
			$json = [];
			foreach( $ruta['route'] as $k => $r)
			{
				foreach( $r as $v)
				{
					$json[$k][] = [
						'lat' => (double)$v['latitude'],
						'lng' => (double)$v['longitude'],
					];
				}
			}

			return $json;
		}
		else
		{
			if(empty($finalRoute))
			{
				throw new HttpException('400', 'No se encontraron paraderos cercanos');
			}

			return $finalRoute;
		}
	}

	public function getWeight($lat1, $lon1, $lat2, $lon2, $distance)
	{
		$smartNode = SmartNode::find()->where(['lat' => $lat1, 'lon' => $lon1]);
		$smartNode2 = SmartNode::find()->where(['lat' => $lat2, 'lon' => $lon2]);

		$sumTraffic = 0;
		$sumVelocity = 0;
		foreach ( $smartNode as $sN)
		{
			$sumTraffic+= $sN['isTraffic'];
			$sumVelocity+= $sN['velocity'];
		}

		$weight = (($sumTraffic*$sumVelocity)/count($smartNode))*$distance;

		//ESTO ES UNA PRUEBA DE GIT

		return $weight;
	}

	public function distance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
	{
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
}