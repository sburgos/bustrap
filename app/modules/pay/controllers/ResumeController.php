<?php

namespace modules\pay\controllers;

use orm\event\Event;
use orm\event\Ticket;


include('culqi.php');

/**
 * IndexController displays a home page for the CRUD
 */
class ResumeController extends \yii\web\Controller
{
	public function actionIndex($ticket, $e = null, $m = null)
	{
		$ticket = Ticket::find()
			->innerJoinWith('ticketPrices')
            ->innerJoinWith('asistant')
			->where(['ticket.id' => $ticket])
			->asArray()
			->one();

        $moneda = $ticket['ticketPrices'][0]['currency'] == 'soles'?'PEN':'USD';
        $monto = $ticket['ticketPrices'][0]['amount'];
        $priceName = $ticket['ticketPrices'][0]['priceName'].' ('.$ticket['ticketPrices'][0]['shareName'].')';

		\Culqi::$codigoComercio = "demo";
		\Culqi::$llaveSecreta = "JlhLlpOB5s1aS6upiioJkmdQ0OYZ6HLS2+/o4iYO2MQ=";
		\Culqi::$servidorBase = 'https://integ-pago.culqi.com';

		$data = \Pago::crearDatospago([
			\Pago::PARAM_NUM_PEDIDO => $ticket['id'].time().rand(0,60),
			\Pago::PARAM_MONEDA => $moneda,
			\Pago::PARAM_MONTO => round($monto*100).'',
			\Pago::PARAM_DESCRIPCION => $priceName,
			\Pago::PARAM_COD_PAIS => "PE",
			\Pago::PARAM_CIUDAD => "Lima",
			\Pago::PARAM_DIRECCION => "AV. 123",
            \Pago::PARAM_NUM_TEL => $ticket['asistant']['dni'],
            "correo_electronico" => $ticket['asistant']['email'],
            "nombres" => $ticket['asistant']['name'],
			"id_usuario_comercio" => $ticket['id'],
			"apellidos" => "Muro",
		]);

		$error = '';
		if($e == 1)
		    $error = 'El procesador de pagos dice: '.htmlentities(base64_decode($m));


		$event = Event::find()->where(['id' => $ticket['eventId']])->one();
		return $this->render('index', [
			'ticket'	=> $ticket,
			'event'		=> $event,
			'error'		=> $error,
			'data'		=> $data,
		]);
	}
}