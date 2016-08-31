<?php

namespace modules\pay\controllers;

use orm\event\Event;
use orm\event\Ticket;


include('culqi.php');

/**
 * IndexController displays a home page for the CRUD
 */
class SharesumeController extends \yii\web\Controller
{
	public function actionIndex($ticket, $e = null, $m = null)
	{
		$ticket = Ticket::find()
			->innerJoinWith("asistant")
			->innerJoinWith("ticketPrices")
			->where([
				'ticket.id'	=> $ticket,
			])
			->one();
		$event = Event::find()->where(['id' => $ticket['eventId']])->one();

		$thearArePrices = false;
		foreach($ticket->ticketPrices as $tp)
		{
			if(!$tp->paid)
			{
				$thearArePrices = true;
			}
		}

		if( ! $thearArePrices)
		{
			$this->redirect("/pay/shares?id=".$event->id.'&e=1&m='.base64_encode('Usted no tiene pagos pendientes para este evento.'));
			return ;
		}

		$error = '';
		if($e == 1)
			$error = 'El procesador de pagos dice: '.htmlentities(base64_decode($m));

		return $this->render('index', [
			'event'			=> $event,
			'ticket'		=> $ticket,
			'error'			=> $error,
		]);
	}

	public function actionResume($id)
	{
		if(\Yii::$app->request->isPost)
		{
			$post = \Yii::$app->request->post();

            $ticket = Ticket::find()
                ->innerJoinWith("asistant")
                ->innerJoinWith("ticketPrices")
                ->where([
                    'ticket.id'	=> $id,
                ])
                ->one();

            $pedido = $ticket->id.time().rand(0,60);
            $total = 0;
            $shareName = [];
            $priceName = '';
            $prices = [];
            foreach($ticket->ticketPrices as $tp)
            {
                if(in_array($tp->id, $post['price'])) {
                    $prices[] = $tp;
                    $moneda = $tp->currency == 'soles'?'PEN':'USD';
                    $total+= $tp->amount;
                    $shareName[]= $tp->shareName;
                    $priceName = $tp->priceName;
                }
            }


            \Culqi::$codigoComercio = "demo";
            \Culqi::$llaveSecreta = "JlhLlpOB5s1aS6upiioJkmdQ0OYZ6HLS2+/o4iYO2MQ=";
            \Culqi::$servidorBase = 'https://integ-pago.culqi.com';

            $data = \Pago::crearDatospago([
                \Pago::PARAM_NUM_PEDIDO     => $pedido,
                \Pago::PARAM_MONEDA         => $moneda,
                \Pago::PARAM_MONTO          => round($total*100).'',
                \Pago::PARAM_DESCRIPCION    => $priceName.' ('.implode(' - ',$shareName).')',
                \Pago::PARAM_COD_PAIS       => "PE",
                \Pago::PARAM_CIUDAD         => "Lima",
                \Pago::PARAM_DIRECCION      => "AV. 123",
                \Pago::PARAM_NUM_TEL        => $ticket->asistant->dni,
                'correo_electronico'        => $ticket->asistant->email,
                'nombres'                   => $ticket->asistant->name,
                'id_usuario_comercio'       => $ticket->id,
                'apellidos'                 => "Muro",
            ]);

			$event = Event::find()->where(['id' => $ticket['eventId']])->one();

			return $this->render('resume', [
				'event'			=> $event,
				'ticket'		=> $ticket,
				'prices'		=> $prices,
                'data'          => $data,
			]);
		}
	}
}