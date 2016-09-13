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

    public function actionIndex($id = 0)
    {
        $lines = Bus::find()->all();

        $json = '';
        if ($id > 0)
        {
            $bus = Bus::find()->where(['id' => $id])->asArray()->one();
            $route = Route::find()->where(['lineId' => $bus['idLine']])->asArray()->all();

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
}