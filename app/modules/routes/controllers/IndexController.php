<?php
namespace modules\routes\controllers;
use common\yii\helpers\ArrayHelper;
use modules\admin\controllers\PromotionCodeController;
use orm\bus\Bus;
use orm\bus\Line;
use orm\bus\Route;

/**
 * Created by PhpStorm.
 * User: sebastianburgos
 * Date: 9/11/16
 * Time: 8:09 PM
 */

class IndexController extends \yii\web\Controller
{

    public function actionIndex($id = 0, $ida = 'no')
    {
        $lines = Bus::find()->all();

        $json = '';
        if ($id > 0)
        {
            $bus = Bus::find()->where(['id' => $id])->asArray()->one();

            if($ida === 'no')
                $route = Route::find()->where(['lineId' => $bus['idLine']])->asArray()->all();
            else
                $route = Route::find()->where(['lineId' => $bus['idLine'], 'ida' => $ida])->asArray()->all();

            foreach($route as $r)
            {
                $json[] = [
                    'lat' => (double)$r['latitude'],
                    'lng' => (double)$r['longitude'],
                ];
            }
        }

        return $this->render('index', [
            'lines' => $lines,
            'json' => $json,
        ]);
    }

    public function actionShort($pos, $id = null)
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
              WHERE
                ida = 1
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
	    $buses = null;
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
				   $route[$k]['route'][1][] = $bus1;

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
				    $route[$k]['route'][2][] = $bus2;

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
		    if(isset($r['route'][1]) and isset($r['route'][2]))
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
				    $route[$bus]['route'][1][] = $r;

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
	    foreach( $route as $k => $r)
	    {
		    if(isset($r['route'][1]))
		    {
			    $bestRoutes[$k] = $r;
		    }
	    }


	    uasort($bestRoutes, function($a, $b){
		    return $a['distance'] > $b['distance'];
	    });

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

		    return $this->render('short-map', [
			    'json' => $json,
		    ]);
	    }
	    else
	    {
		    return $this->render('short', [
			    'bus' => $bestRoutes
		    ]);
	    }
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