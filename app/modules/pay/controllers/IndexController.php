<?php

namespace modules\pay\controllers;

use Faker\Provider\DateTime;
use orm\event\Assistant;
use orm\event\Event;
use orm\event\Price;
use orm\event\Share;
use orm\event\Ticket;
use orm\event\TicketLog;
use orm\event\TicketPrices;
use Yii;
use common\yii\gii\generators\crud\event\CrudEvent;

/**
 * IndexController displays a home page for the CRUD
 */
class IndexController extends \yii\web\Controller
{
	/**
	 * Lists all Movie models.
	 * @return mixed
	 */
	public function actionIndex($id = null)
	{
		if(empty($id))
		{
			$this->redirect("/pay/index/error");
			return ;
		}

		date_default_timezone_set("America/Lima");
		$now = date("Y-m-d H:i:s");
		$error = '';

		if(\Yii::$app->request->isPost)
		{
			$post = \Yii::$app->request->post();

            if(empty($post['radio']) or empty($post['nombre']) or empty($post['correo']) or empty($post['documento']))
            {
                $error = 'Debe llenar todos los campos. No olvide seleccionar un tipo de precio.';
            }
            else
            {


                $eventPrice = Price::find()
                    ->where(['price.id' => $post['radio']])
                    ->innerJoinWith("shares")
                    ->asArray()
                    ->one();

                if ($eventPrice['eventId'] != $id) {
                    $error = 'El precio seleccionado no pertenece a este evento';
                }

                $assistant = new Assistant();
                $assistant->name = $post['nombre'];
                $assistant->email = $post['correo'];
                $assistant->dni = $post['documento'];
                $assistant->eventId = $id;
                $assistant->status = 0;
                $asistantId = $assistant->save();
                if (!$asistantId) {
                    $error = 'No se puede generar la compra. Verifica que no estes registrado en el evento y que todos tus datos son correctos' . json_encode($assistant->getErrors());
                } else {
                    $ticket = new Ticket();
                    $ticket->eventId = $id;
                    $ticket->asistantId = $assistant->id;
                    $ticket->status = 0;
                    $ticket->sellingDate = date("Y-m-d H:i:s");
                    $ticketId = $ticket->save();

                    if (!$ticketId) {
                        $assistant->delete();
                        $error = 'Hubo un error al generar el ticket. Intenta nuevamente.' . json_encode($ticket->getErrors());
                    } else {
                        $this->logTicket($ticket, 'Ticket Creado', 'El ticket se creó sin ningún problema');

                        foreach ($eventPrice['shares'] as $share) {
                            $this->logTicket($ticket, 'Se le asignó cuota aún sin pagar', $share['name'] . ' (' . $share['amount'] . ' en moneda: ' . $eventPrice['currency'] . ')');
                            $ticketPrices = new TicketPrices();
                            $ticketPrices->ticketId = $ticket->id;
                            $ticketPrices->priceId = $eventPrice['id'];
                            $ticketPrices->priceName = $eventPrice['name'];
                            $ticketPrices->shareId = $share['id'];
                            $ticketPrices->shareName = $share['name'];
                            $ticketPrices->currency = $eventPrice['currency'];
                            $ticketPrices->amount = $share['amount'];
                            $ticketPrices->paid = 0;
                            $ticketPrices->save();
                        }
                    }
                }

                if (empty($error))
                    $this->redirect("/pay/resume?ticket=" . $ticket->id);
            }

		}

		$event = Event::find()
			->where([
				'id' => $id,
			])
			->andWhere(['<=', 'toDate', $now])
			->andWhere(['>=', 'fromDate', $now])
			->one();

		if(empty($event))
		{
			$this->redirect("/pay/index/error");
			return ;
		}

		$prices = Price::find()
			->innerJoinWith("shares")
			->where([
				'eventId' => $id,
			])
			->andWhere(['<=', 'toDate', $now])
			->andWhere(['>=', 'fromDate', $now])
			->asArray()
			->all();
		if(empty($prices))
		{
			$this->redirect("/pay/index/error");
			return ;
		}

		return $this->render('index', [
			'event'		=> $event,
			'prices' 	=> $prices,
			'error'		=> $error,
		]);
	}

	public function actionError()
	{
		return $this->render('error');
	}

	public function logTicket($ticket, $title, $msg)
	{
		$ticketLog = new TicketLog();
		$ticketLog->title = $title;
		$ticketLog->ticketId = $ticket->id;
		$ticketLog->message = $msg;
		$ticketLog->rowDate = date("Y-m-d H:i:s");

		$ticketLog->save();
	}
}