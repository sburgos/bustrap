<?php

namespace crud\bus\controllers;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;

/**
 * IndexController displays a home page for the CRUD
 */
class IndexController extends \yii\web\Controller
{
    /**
     * Lists all Bus models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$allLinks = [
			'bus' => ['label' => 'Buses', 'url' => ['bus/index']],
			'data' => ['label' => 'Datas', 'url' => ['data/index']],
			'line' => ['label' => 'Lines', 'url' => ['line/index']],
			'route' => ['label' => 'Routes', 'url' => ['route/index']],
			'smart-node' => ['label' => 'Smart Nodes', 'url' => ['smart-node/index']],
			'smart-routes' => ['label' => 'Smart Routes', 'url' => ['smart-routes/index']],
		];
    	$event = new CrudEvent(['moduleId' => 'bus', 'links' => $allLinks]);
    	Yii::$app->trigger(CrudEvent::FILTER_INDEX_PAGE_LINKS, $event); 
    	
        return $this->render('index', [
            'links' => $event->links,
            'moduleId' => 'bus',
        ]);
    }
}
