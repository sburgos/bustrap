<?php

namespace modules\pay\controllers;

use orm\event\Event;
use orm\event\Ticket;


class SuccesshareController extends \yii\web\Controller
{
	public function actionIndex($ticket, $p = null)
	{
		echo "ok";die();
	
		/*$ticket = Ticket::find()
			->innerJoinWith('ticketPrices')
			->innerJoinWith('event')
			->where(['ticket.id' => $ticket, "ticket.status" => 1])
			->asArray()
			->one();
		if(empty($ticket))
		{
			return $this->render('error');
		}

		$p = json_decode( base64_decode( $p ) ,true);

		$prices = [];
		foreach($ticket->ticketPrices as $tp)
		{
			if(in_array($tp->id, $p))
			{
				$prices[] = $p;
			}
		}

		$event = Event::find()->where(['id' => $ticket['eventId']])->one();

		return $this->render('index', [
			'ticket'	=> $ticket,
			'event'		=> $event,
			'prices'	=> $prices,
		]);
	}*/
}