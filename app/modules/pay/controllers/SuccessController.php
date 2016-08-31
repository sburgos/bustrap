<?php

namespace modules\pay\controllers;

use orm\event\Event;
use orm\event\Ticket;


class SuccessController extends \yii\web\Controller
{
	public function actionIndex($ticket)
	{
		$ticket = Ticket::find()
            ->innerJoinWith('ticketPrices')
            ->innerJoinWith('event')
            ->where(['ticket.id' => $ticket, "ticket.status" => 1])
            ->asArray()
            ->one();
		if(empty($ticket))
		{
			return $this->render('error');
		}

		$event = Event::find()->where(['id' => $ticket['eventId']])->one();

		return $this->render('index', [
			'ticket'	=> $ticket,
			'event'		=> $event,
		]);
	}
}