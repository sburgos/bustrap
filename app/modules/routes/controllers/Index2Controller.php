<?php
namespace modules\routes\controllers;
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

	public function actionShort($pos)
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
		foreach($position as $p)
		{
			$sql = "
              SELECT 
                  lineId,
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

			echo "<pre>";print_r($pp);die();

			foreach( $pp as $posi)
			{
				if($i == 0)
					$lines[$posi['lineId']] = 'nada';
				else
				{
					if(isset($lines[$posi['lineId']]))
					{
						$lines[$posi['lineId']] = 'ida';
					}
				}
			}
			$i++;
		}
		$newLines = [];
		foreach($lines as $k => $v)
		{
			if($v != 'nada')
			{
				$newLines[$k] = $v;
			}
		}
		$lines = [];

		foreach($position as $p)
		{
			$sql = "
              SELECT 
                  lineId,
                  ( 6371 * acos( cos( radians(".$p['latitude'].") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$p['longitude'].") ) + sin( radians(".$p['latitude'].") ) * sin( radians( latitude ) ) ) ) AS distance 
              FROM 
                route
              WHERE
                ida = 0
              HAVING 
                distance < '".$km."' 
              ORDER BY 
                distance ";

			$pp = \Yii::$app->db->createCommand($sql)->queryAll();

			foreach( $pp as $posi)
			{
				if($i == 0)
					$lines[$posi['lineId']] =  'nada';
				else
				{
					if(isset($lines[$posi['lineId']]))
					{
						$lines[$posi['lineId']] = 'vuelta';
					}
				}
			}
			$i++;
		}
		foreach($lines as $k => $v)
		{
			if($v != 'nada')
			{
				$newLines[$k] = $v;
			}
		}

		$bus = Bus::find()->where(['idLine' => array_keys($newLines)])->asArray()->all();

		$busArray = [];
		foreach($bus as $b)
		{
			$busArray[] = [
				'id' => $b['id'],
				'name' => $b['name'],
				'lineId' => $b['idLine'],
				'ida'   => $newLines[$b['idLine']] == 'ida',
			];
		}

		return $this->render('short', [
			'bus' => $busArray
		]);
	}
}