<?php

namespace modules\pay\controllers;

use orm\event\Event;
use orm\event\Ticket;
use orm\event\TicketLog;
use orm\event\TicketPrices;


include('culqi.php');

/**
 * IndexController displays a home page for the CRUD
 */
class ProccessController extends \yii\web\Controller
{
    public function actionIndex($ticket)
    {
        if(\Yii::$app->request->isPost)
        {
            $post = \Yii::$app->request->post();
            $info = $post['informacionDeVentaCifrada'];
            if($info == 'parametro_invalido')
            {
                $this->redirect("/pay/resume?ticket=".$ticket.'&e=1');
            }
            $this->logTicket($ticket, "Información de venta cifrada", $info);
            \Culqi::$llaveSecreta = 'JlhLlpOB5s1aS6upiioJkmdQ0OYZ6HLS2+/o4iYO2MQ=';
            $datosDeVentaRealizada = \Culqi::decifrar($info, true);
            //print_r($datosDeVentaRealizada);die();
            $this->logTicket($ticket, "Información de venta descifrada", $datosDeVentaRealizada);
            // Se convierte en array los datos de la venta
            $datosDeVentaRealizada = json_decode($datosDeVentaRealizada, true);

            if($datosDeVentaRealizada['codigo_respuesta'] == 'venta_exitosa')
            {
                $ticket = Ticket::find()
                    ->where(["id" => $ticket])
                    ->one();
                $ticket->status = 1;
                $ticket->save();

                $ticketPrices = TicketPrices::find()
                    ->where(["ticketId" => $ticket->id])
                    ->one();
                $ticketPrices->paid = 1;
                $ticketPrices->save();

                $this->redirect("/pay/success?ticket=".$ticket->id);
            }
            else
            {
                $this->redirect("/pay/resume?ticket=".$ticket.'&e=1&m='.base64_encode($datosDeVentaRealizada['mensaje_respuesta_usuario']));
            }
        }
    }

    public function actionShare($ticket)
    {
        if(\Yii::$app->request->isPost)
        {
            $post = \Yii::$app->request->post();
            $prices = json_decode(base64_decode($post['prices']), true);
            $info = $post['informacionDeVentaCifrada'];
            if($info == 'parametro_invalido')
            {
                $this->redirect("/pay/resume?ticket=".$ticket.'&e=1');
            }
            $this->logTicket($ticket, "Información de venta cifrada", $info);
            try
            {
                \Culqi::$llaveSecreta = 'JlhLlpOB5s1aS6upiioJkmdQ0OYZ6HLS2+/o4iYO2MQ=';
                $datosDeVentaRealizada = \Culqi::decifrar($info, true);
                //print_r($datosDeVentaRealizada);die();
                $this->logTicket($ticket, "Información de venta descifrada", $datosDeVentaRealizada);
                // Se convierte en array los datos de la venta
                $datosDeVentaRealizada = json_decode($datosDeVentaRealizada, true);
            }catch(\Exception $e)
            {
                $this->redirect("/pay/sharesume?ticket=".$ticket.'&e=1&m='.base64_encode('Hubo un error al procesar tu compra. Intenta otra vez'));
                return ;
            }


            if($datosDeVentaRealizada['codigo_respuesta'] == 'venta_exitosa')
            {
                $ticket = Ticket::find()
                    ->where(["id" => $ticket])
                    ->one();

                $ticketPrices = TicketPrices::find()
                    ->where(["ticketId" => $ticket->id])
                    ->all();
                $preciosCorrectos = false;
                foreach($ticketPrices as $tp)
                {
                    if(in_array($tp->id, $prices))
                    {
                        $tp->paid = 1;
                        $tp->save();
                        $preciosCorrectos = true;
                    }
                }
                if($preciosCorrectos)
                {
                    $ticket->status = 1;
                    $ticket->save();

                    $this->redirect("/pay/suces?ticket=" . $ticket->id . "&p=" . base64_encode( json_encode($prices) ) );
                    return ;
                }
                else
                {
                    $this->redirect("/pay/sharesume?ticket=".$ticket.'&e=1&m='.base64_encode('Los precios enviados no corresponden al ticket. Favor de verificar'));
                    return ;
                }

            }
            else
            {
                $this->redirect("/pay/sharesume?ticket=".$ticket.'&e=1&m='.base64_encode($datosDeVentaRealizada['mensaje_respuesta_usuario']));
                return ;
            }
        }
    }

    public function logTicket($ticket, $title, $msg)
    {
        $ticketLog = new TicketLog();
        $ticketLog->title = $title;
        $ticketLog->ticketId = $ticket;
        $ticketLog->message = $msg;
        $ticketLog->rowDate = date("Y-m-d H:i:s");

        $ticketLog->save();
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }
}