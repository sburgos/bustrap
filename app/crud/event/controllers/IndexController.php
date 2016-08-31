<?php

namespace crud\event\controllers;

use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;

/**
 * IndexController displays a home page for the CRUD
 */
class IndexController extends \yii\web\Controller
{
    /**
     * Lists all Event models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$allLinks = [
			'assistant' => ['label' => 'Assistants', 'url' => ['assistant/index']],
			'event' => ['label' => 'Events', 'url' => ['event/index']],
			'manager' => ['label' => 'Managers', 'url' => ['manager/index']],
			'price' => ['label' => 'Prices', 'url' => ['price/index']],
			'share' => ['label' => 'Shares', 'url' => ['share/index']],
			'ticket' => ['label' => 'Tickets', 'url' => ['ticket/index']],
			'ticket-log' => ['label' => 'Ticket Logs', 'url' => ['ticket-log/index']],
			'ticket-prices' => ['label' => 'Ticket Prices', 'url' => ['ticket-prices/index']],
		];
    	$event = new CrudEvent(['moduleId' => 'event', 'links' => $allLinks]);
    	Yii::$app->trigger(CrudEvent::FILTER_INDEX_PAGE_LINKS, $event); 
    	
        return $this->render('index', [
            'links' => $event->links,
            'moduleId' => 'event',
        ]);
    }
}
