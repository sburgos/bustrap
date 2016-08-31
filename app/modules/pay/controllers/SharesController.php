<?php

namespace modules\pay\controllers;

use orm\event\Event;
use orm\event\Price;
use orm\event\Ticket;
use orm\event\TicketPrices;



/**
 * IndexController displays a home page for the CRUD
 */
class SharesController extends \yii\web\Controller
{
	public function actionIndex($id, $e = null, $m = null)
	{
		$error = '';
		$now = date("Y-m-d H:i:s");

		$event = Event::find()
			->where([
				'id' => $id,
			])
			->andWhere(['<=', 'toDate', $now])
			->andWhere(['>=', 'fromDate', $now])
			->one();

		if(\Yii::$app->request->isPost)
		{
			$post = \Yii::$app->request->post();
			$ticket = Ticket::find()
				->innerJoinWith("asistant")
				->innerJoinWith("ticketPrices")
				->where([
					'assistant.email'	=> $post['correo'],
					'assistant.dni'		=> $post['documento']
				])

				->one();

			if(empty($ticket))
			{
				$error = 'No tiene ningún ticket para este evento.';
			}
			else
			{
				$pendientes = false;

				foreach($ticket->ticketPrices as $tp)
				{
					if(!$tp->paid)
					{
						$pendientes = true;
						break;
					}
				}
				if(!$pendientes)
				{
					$error = 'Usted ya no tiene ningún pago pendiente';
				}
				else
				{
					$this->redirect("/pay/sharesume?ticket=".$ticket->id);
				}
			}

		}

		if($e == 1)
			$error = htmlentities(base64_decode($m));


		return $this->render('index', [
			'event'		=> $event,
			'error'		=> $error,
		]);
	}
}