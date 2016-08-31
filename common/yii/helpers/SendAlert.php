<?php
namespace common\yii\helpers;
 
use orm\v1\mongo\AppAlert;

class SendAlert
{
	/**
	 * Base method to send alerts when an event information failed
	 * @param unknown $msg
	 * @param unknown $appId
	 * @param unknown $eventTime
	 * @param unknown $pricesResult
	 * @return Ambigous <void, boolean>
	 */
	public static function __eventTimeGetFailed($msg, $appId, $eventTime, $pricesResult)
	{
		try {
			$destinataries = [
				'atcliente@cinepapaya.com',
				'cartelera@cinepapaya.com',
			];
			$countryId = '??';
			$responsability = '??';
			$url = '';
			$eventDetail = '';
			if ($appId == 'cine')
			{
				$countryId = $eventTime->movieTheater->movieTheaterChain->country->id;
				$responsability = $eventTime->movieTheater->movieTheaterChain->name;
				$url = "https://admin.cinepapaya.com/cine/crud/movie-time/view?id=" . $eventTime->id;
				$eventDetail = "'" . $eventTime->movie->name . "' - " . $eventTime->exhibitMode . " " .
					$eventTime->exhibitLanguage . " - Sala " . $eventTime->screenNumber . " - " .
					$eventTime->exhibitionDate;
			}
			return static::__sendMedium(
				$msg,
				$eventTime->eventOwnerName . " {$msg} para " . $eventDetail,
				[
					'Id Funcion' => $eventTime->id,
					'Detalle del evento' => $eventDetail,
					'Url prueba' => $url,
					'Respuesta del Proveedor' => $pricesResult->getResponse(),
					'Request' => $pricesResult->getRequest(),
					'Errores' => $pricesResult->getErrors(),
				],
				[
					'appId' => $appId,
					'countryId' => $countryId,
					'responsability' => $responsability,
					'cacheKey' => __FUNCTION__ . $appId . $msg . $eventTime->id,
					'cacheDuration' => 120,
					'destinataries' => $destinataries,
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * Getting prices from event provider failed
	 * 
	 * @param unknown $appId
	 * @param unknown $eventTime
	 * @param unknown $pricesResult
	 * @return \common\yii\helpers\Ambigous
	 */
	public static function eventTimeGetPricesFailed($appId, $eventTime, $pricesResult)
	{
		return static::__eventTimeGetFailed(
			"No devuelve precios",
			$appId, 
			$eventTime, 
			$pricesResult
		);
	}
	
	/**
	 * When getting prices and none of the prices obtained is for seat type
	 *  
	 * @param unknown $appId
	 * @param unknown $eventTime
	 * @param unknown $pricesResult
	 * @return \common\yii\helpers\Ambigous
	 */
	public static function eventTimeGetPricesNoSeatsFound($appId, $eventTime, $pricesResult)
	{
		return static::__eventTimeGetFailed(
			"No tiene precios asignados",
			$appId,
			$eventTime,
			$pricesResult
		);
	}
	
	/**
	 * When geting the map from the event provider failed
	 * @param unknown $appId
	 * @param unknown $eventTime
	 * @param unknown $mapResult
	 * @return \common\yii\helpers\Ambigous
	 */
	public static function eventTimeGetMapFailed($appId, $eventTime, $mapResult)
	{
		return static::__eventTimeGetFailed(
			"No devuelve mapa",
			$appId,
			$eventTime,
			$mapResult
		);
	}
	
	/**
	 * When something went wrond during the payment process
	 * @param unknown $msg
	 * @param unknown $ticket
	 * @param unknown $processResult
	 * @param unknown $to
	 * @param string $sendMethodName
	 * @param string $extraMsg
	 * @return Ambigous <void, boolean>
	 */
	public static function __payProcessFailed($msg, $ticket, $processResult, $to, 
		$sendMethodName = '__sendMedium', $extraMsg = "")
	{
		try {
			$destinataries = $to;
			$countryId = $ticket->countryId;
			$responsability = '??';
			$url = '';
			if ($ticket->appId == 'cine')
			{
				if ($ticket->ticketCineInfo && $ticket->ticketCineInfo->movieTheater)
					if ($ticket->ticketCineInfo->movieTheater->movieTheaterChain)
						$responsability = $ticket->ticketCineInfo->movieTheater->movieTheaterChain->name;
				$url = "https://admin.cinepapaya.com/pay/crud/ticket/view?id=" . $ticket->id;
			}
			return static::$sendMethodName(
				$msg,
				"{$msg} para el ticket con id {$ticket->id}",
				[
					'Id del ticket' => $ticket->id,
					'Url prueba' => $url,
					'Info adicional' => $extraMsg,
					'Resultado del proceso' => $processResult,
				],
				[
					'appId' => $ticket->appId,
					'countryId' => $countryId,
					'responsability' => $responsability,
					'cacheKey' => __FUNCTION__ . $ticket->appId . $msg . $ticket->id,
					'cacheDuration' => 120,
					'destinataries' => $destinataries,
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * Cuando falle la reserva
	 * 
	 * @param unknown $ticket
	 * @param unknown $reservationResult
	 * @return \common\yii\helpers\Ambigous
	 */
	public static function reservationFailed($ticket, $reservationResult)
	{
		return static::__payProcessFailed(
			"Error al realizar una reserva", 
			$ticket, 
			$reservationResult,
			[], // Do not send email here 
			'__sendLow'
		);
	}
	
	/**
	 * Cuando falle la compra
	 * 
	 * @param unknown $ticket
	 * @param unknown $purchaseResult
	 * @return \common\yii\helpers\Ambigous
	 */
	public static function purchaseFailed($ticket, $purchaseResult)
	{
		return static::__payProcessFailed(
			"Error al registrar la compra en el cine", 
			$ticket, 
			$purchaseResult,
			['desarrollo@cinepapaya.com', 'operaciones@cinepapaya.com'],
			'__sendHigh'
		);
	}
	
	/**
	 * Cuando falle la captura
	 * 
	 * @param unknown $ticket
	 * @param unknown $captureResult
	 * @return \common\yii\helpers\Ambigous
	 */
	public static function captureFailed($ticket, $captureResult)
	{
		return static::__payProcessFailed(
			"Error al capturar el pago",
			$ticket,
			$captureResult,
			['desarrollo@cinepapaya.com', 'operaciones@cinepapaya.com'],
			'__sendCritical',
			"Esto nunca debería pasar. Revisar el ticket a mano"
		);
	}
	
	/**
	 * Cuando falle la liberacion de asientos
	 * 
	 * @param unknown $ticket
	 * @param unknown $undoReserveResult
	 * @return \common\yii\helpers\Ambigous
	 */
	public static function undoReservationFailed($ticket, $undoReserveResult)
	{
		return static::__payProcessFailed(
			"Error al anular la reserva de asientos",
			$ticket,
			$undoReserveResult,
			['desarrollo@cinepapaya.com', 'operaciones@cinepapaya.com'],
			'__sendMedium',
			"Ha ocurrido un error al intentar anular la reserva"
		);
	}
	
	/**
	 * Cuando se detecta fraude y se previene la compra
	 * 
	 * @param \orm\v1\pay\Ticket $ticket
	 * @param boolean $sendByEmail
	 * @param [] $fraudMessages
	 * @return Ambigous <void, boolean>
	 */
	public static function fraudDetected($ticket, $sendByEmail, $fraudMessages)
	{
		try {
			return static::__sendMedium("Fraude detectado",
				"Se ha detectado fraude y se previno la compra del ticket",
				[
					'Id ticket' => $ticket->id,
					'Invitado' => $ticket->ticketCustomerInfo->isGuest ? 'Si' : 'No',
					'Correo' => $ticket->ticketCustomerInfo->customerEmail,
					'Razones' => $fraudMessages,
				],
				[
					'appId' => $ticket->appId,
					'countryId' => $ticket->countryId,
					'cacheKey' => __FUNCTION__ . $ticket->id,
					'cacheDuration' => 120,
					'destinataries' => $sendByEmail ? ['operaciones@cinepapaya.com'] : [],
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * Cuando se detecta un posible fraude pero la compra continua
	 *
	 * @param \orm\v1\pay\Ticket $ticket
	 * @param boolean $sendByEmail
	 * @param [] $fraudMessages
	 * @return Ambigous <void, boolean>
	 */
	public static function possibleFraudDetected($ticket, $sendByEmail, $fraudMessages)
	{
		try {
			return static::__sendMedium("Posible Fraude detectado",
				"Se ha detectado un posible fraude pero la compra continuó. Revisar a mano",
				[
					'Id ticket' => $ticket->id,
					'Invitado' => $ticket->ticketCustomerInfo->isGuest ? 'Si' : 'No',
					'Correo' => $ticket->ticketCustomerInfo->customerEmail,
					'Razones' => $fraudMessages,
				],
				[
					'appId' => $ticket->appId,
					'countryId' => $ticket->countryId,
					'cacheKey' => __FUNCTION__ . $ticket->id,
					'cacheDuration' => 120,
					'destinataries' => $sendByEmail ? ['operaciones@cinepapaya.com'] : [],
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * When sending a notification of a method post ticket purchase fails
	 * 
	 * @param unknown $notificationClass
	 * @param unknown $ticket
	 * @return Ambigous <void, boolean>
	 */
	public static function sendingNotificationFailed($notificationClass, $ticket)
	{
		try {
			return static::__sendMedium("Error envio notificacion", 
				"Ha ocurrido un error al enviar la notificación de un ticket", 
				[
					'Id ticket' => $ticket->id,
					'Notification' => $notificationClass,
				], 
				[
					'appId' => $ticket->appId,
					'countryId' => $ticket->countryId,
					'cacheKey' => __FUNCTION__ . $notificationClass . $ticket->id,
					'cacheDuration' => 120,
					'destinataries' => ['desarrollo@cinepapaya.com'],
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * When the reservation took longer than the specified threshold
	 * 
	 * @param unknown $appId
	 * @param unknown $countryId
	 * @param unknown $providerClass
	 * @param unknown $duration
	 * @return Ambigous <void, boolean>
	 */
	public static function reservationDurationAboveThreshold($appId, $countryId, $providerClass, $duration)
	{
		try {
			$duration = round($duration, 3);
			$eventProviderName = StringHelper::basename(get_class($providerClass));
			return static::__sendLow("Servicio reserva lento",
				"Ha tomado {$duration} segundos realizar una reserva en {$eventProviderName}",
				[
					'Id ticket' => $ticket->id,
					'Provider Class' => $providerClass,
					'Proveedor' => $eventProviderName,
				],
				[
					'appId' => $appId,
					'countryId' => $countryId,
					'responsability' => $eventProviderName,
					'cacheKey' => __FUNCTION__ . $providerClass,
					'cacheDuration' => 120,
					'destinataries' => ['desarrollo@cinepapaya.com'],
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * When the purchase took longer than the specified threshold
	 * 
	 * @param unknown $appId
	 * @param unknown $countryId
	 * @param unknown $providerClass
	 * @param unknown $duration
	 * @return Ambigous <void, boolean>
	 */
	public static function purchaseDurationAboveThreshold($appId, $countryId, $providerClass, $duration)
	{
		try {
			$duration = round($duration, 3);
			$eventProviderName = StringHelper::basename(get_class($providerClass));
			return static::__sendLow("Servicio compra lento",
				"Ha tomado {$duration} segundos registrar una compra en {$eventProviderName}",
				[
					'Id ticket' => $ticket->id,
					'Provider Class' => $providerClass,
					'Proveedor' => $eventProviderName,
				],
				[
					'appId' => $appId,
					'countryId' => $countryId,
					'responsability' => $eventProviderName,
					'cacheKey' => __FUNCTION__ . $providerClass,
					'cacheDuration' => 120,
					'destinataries' => ['desarrollo@cinepapaya.com'],
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * When the authorization took longer than the specified threshold
	 * @param unknown $appId
	 * @param unknown $countryId
	 * @param unknown $providerClass
	 * @param unknown $duration
	 * @return Ambigous <void, boolean>
	 */
	public static function authorizationDurationAboveThreshold($appId, $countryId, $providerClass, $duration)
	{
		try {
			$duration = round($duration, 3);
			$eventProviderName = StringHelper::basename($providerClass);
			return static::__sendLow("Servicio autorizacion lento",
				"Ha tomado {$duration} segundos realizar una autorizacion en {$eventProviderName}",
				[
					'Id ticket' => $ticket->id,
					'Provider Class' => $providerClass,
					'Proveedor' => $eventProviderName,
				],
				[
					'appId' => $appId,
					'countryId' => $countryId,
					'responsability' => $eventProviderName,
					'cacheKey' => __FUNCTION__ . $providerClass,
					'cacheDuration' => 120,
					'destinataries' => ['desarrollo@cinepapaya.com'],
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	/**
	 * When the capture took longer than the specified threshold
	 * @param unknown $appId
	 * @param unknown $countryId
	 * @param unknown $providerClass
	 * @param unknown $duration
	 * @return Ambigous <void, boolean>
	 */
	public static function captureDurationAboveThreshold($appId, $countryId, $providerClass, $duration)
	{
		try {
			$duration = round($duration, 3);
			$eventProviderName = StringHelper::basename(get_class($providerClass));
			return static::__sendLow("Servicio captura lento",
				"Ha tomado {$duration} segundos realizar una autorizacion en {$eventProviderName}",
				[
					'Id ticket' => $ticket->id,
					'Provider Class' => $providerClass,
					'Proveedor' => $eventProviderName,
				],
				[
					'appId' => $appId,
					'countryId' => $countryId,
					'responsability' => $eventProviderName,
					'cacheKey' => __FUNCTION__ . $providerClass,
					'cacheDuration' => 120,
					'destinataries' => ['desarrollo@cinepapaya.com'],
				]
			);
		}
		catch (\Exception $ex)
		{
			return static::__sendCritical('SEND ALERT ERROR', 'Error al enviar con SendAlert', [
				'function' => __FUNCTION__,
				'exMessage' => $ex->getMessage(),
				'trace' => $ex->getTraceAsString(),
			], ['destinataries' => 'desarrollo@cinepapaya.com']);
		}
	}
	
	
	// Only to developers
	// ------------------
	public static function validateMapSelectionFailed($message, $appId, $restClientId, $eventTime, $post)
	{
		// Esto por lo general indica que hay un error en la aplicación que
		// consume nuestro servicio (no está validando correctamente antes de
		// enviar los datos o en el BaseEventProvider al generar el mapa
		if (is_array($post) && isset($post) && isset($post['number']))
			$post['number'] = 'CC NUMBER UNSETTED FOR SECURITY PURPOSES';
		return static::__sendCritical('Validar map selection failed',
			$message,
			[
				'msg' => "Revisar la aplicación que está consumiendo estos servicios",
				'appId' => $appId,
				'restClientId' => $restClientId,
				'eventTime' => $eventTime,
				'post' => $post,
			],
			[]
		);
	}
	
	public static function gettingExchangeRatesFailed($fromCurrency, $toCurrency)
	{
		// Critical. Purchases will not go through until solved
		return static::__sendCritical('Getting Exchange Rates Failed',
			"No se pudo obtener los tipos de cambio",
			['monedaOrigen' => $fromCurrency, 'monedaDestino' => $toCurrency], 
			[]
		);
	}
	
	public static function gettingBinInfoFailed($bin, $values)
	{
		return static::__sendCritical('Getting Bin Info Failed', 
			"No se pudo obtener el bin {$bin} desde bin db", 
			$values, []
		);
	}
	
	public static function savingToDataBaseFailed($record, $extra = null)
	{
		// Critical
		return static::__sendCritical('SavingToDbFailed', get_class($record),[
			'values' => method_exists($record, 'toArray') ? $record->toArray() : ['no method toArray'],
			'errors' => method_exists($record, 'getErrors') ? $record->getErrors() : ['no method getErrors'],
			'extra' => $extra,
		]);
	}
	
	public static function refundFailed($ticket, $refundResult)
	{
		// Failed but continued without problems
	}
	
	/**
	 * When an exception was raised that should not have happened but it was
	 * handled gracefully (not thrown) by our system. You can pass extra information
	 * to be passed to the alert
	 * 
	 * @param \Exception $exception
	 * @param null|array $extra
	 */
	public static function handledException($exception, $extra = null)
	{
		// Critical
		return static::__sendCritical('HandledException', $exception->getMessage(),[
			'msg' => $exception->getMessage(),
			'file' => $exception->getFile(),
			'line' => $exception->getLine(),
			'code' => $exception->getCode(),
			'extra' => $extra,
			'trace' => $exception->getTraceAsString(),
		]);
	}
	
	/**
	 * When there is a configuration error on our system
	 * 
	 * @param unknown $appId
	 * @param unknown $message
	 * @param unknown $values
	 * @return Ambigous <void, boolean>
	 */
	public static function configurationError($appId, $message, $values)
	{
		// Some configuration error in our side
		return static::__sendCritical('ConfigurationError', $message, $values, []);
	}
	
	// Critical to Developers and Operations
	// -------------------------------------
	public static function refundFailedOnCriticalEvent($ticket, $refundResult)
	{
		// Failed and stopped because could not uncapture
		return static::__sendCritical('RefundFailedOnCriticalEvent', 
			'Se intentó hacer un reembolso pero este falló', [
				'ticket' => $ticket->id,
				'resultado' => $refundResult,
			], [
				'destinataries' => ['desarrollo@cinepapaya.com', 'operaciones@cinepapaya.com'],
			]);
	}
	
	public static function refundSuccessButUndoPurchaseFailed($ticket, $undoPurchaseResult)
	{
		// After a refund but undo purchase failed
		return static::__sendLow('refundSuccessButUndoPurchaseFailed',
			'Se hizo un reembolso pero no se notificó al proveedor del evento', [
				'Razon' => 'Por lo general esto ocurre con devoluciones fuera de fecha',
				'ticket' => $ticket->id,
				'resultado' => $undoPurchaseResult,
			], [
				'destinataries' => ['desarrollo@cinepapaya.com', 'operaciones@cinepapaya.com'],
			]);
	}
	
	//------------------------------------------------------------------------------------
	// SEND HELPER METHODS
	//------------------------------------------------------------------------------------
	protected static function __sendLow($type, $subject, $data = null, $options = null)
	{
		return static::____send($type, $subject, $data, array_merge([
			'severity' => AppAlert::SEVERITY_LOW,
		], empty($options) ? [] : $options));
	}
	
	protected static function __sendMedium($type, $subject, $data = null, $options = null)
	{
		return static::____send($type, $subject, $data, array_merge([
			'severity' => AppAlert::SEVERITY_MEDIUM,
		], empty($options) ? [] : $options));
	}
	
	protected static function __sendHigh($type, $subject, $data = null, $options = null)
	{
		return static::____send($type, $subject, $data, array_merge([
			'severity' => AppAlert::SEVERITY_HIGH,
		], empty($options) ? [] : $options));
	}
	
	protected static function __sendCritical($type, $subject, $data = null, $options = null)
	{
		$options = empty($options) ? [] : $options;
		if (!isset($options['destinataries']))
			$options['destinataries'] = ['desarrollo@cinepapaya.com'];
		
		return static::____send($type, $subject, $data, array_merge([
			'severity' => AppAlert::SEVERITY_CRITICAL,
		], empty($options) ? [] : $options));
	}
	
	//------------------------------------------------------------------------------------
	// SEND METHOD. ALL REQUESTS GO THROUGH HERE
	//------------------------------------------------------------------------------------
	protected static function ____send($type, $subject, $data = null, $options = null)
	{
		try {
			// Prepare AppAlert values
			$options = array_merge([
				'appId' => '??',
				'countryId' => '??',
				'severity' => AppAlert::SEVERITY_LOW,
				'type' => empty($type) ? "??" : substr($type, 0, 50),
				'responsability' => 'Papaya',
				'subject' => empty($subject) ? '??' : substr($subject, 0, 200),
				'dataJson' => empty($data) ? null : json_encode($data),
				'cacheKey' => strtoupper($type),
				'cacheDuration' => 120,
				'destinataries' => [],
			], empty($options) ? [] : $options);
			
			// Create the alert
			$appAlert = new AppAlert();
			try {
				$appAlert->setAttributes($options, false);
				$appAlert->save();
			}
			catch (\Exception $ex1) {
				// If failed so the alert was not registered
			}
			
			// If no destinataries then just end
			if (!is_array($options['destinataries']) || count($options['destinataries']) == 0)
				return;
			
			// Check if the mail should be send or it was already sent
			// X seconds before so we do not receive a lot of emails in
			// a short period of time
			$cache = \Yii::$app->cache;
			$cacheKey = 'APP_ALERT_CACHE_' . strtoupper($options['cacheKey']);
			if ($options['cacheDuration'] > 0)
			{
				$alreadySent = $cache->get($cacheKey);
				if ($alreadySent === true && YII_ENV != 'dev')
					return;
			}
			
			$errorColor = '#333333';
			switch ($appAlert->severity)
			{
				case AppAlert::SEVERITY_CRITICAL:
					$errorColor = '#ff0000';
					break;
				case AppAlert::SEVERITY_HIGH:
					$errorColor = '#cc3300';
					break;
				case AppAlert::SEVERITY_MEDIUM:
					$errorColor = '#CC9900';
					break;
				case AppAlert::SEVERITY_LOW:
					$errorColor = '#333333';
					break;
			}
			
			// Attach URL of the request
			$url_key = 'url';
			while (isset($data[$url_key]))
				$url_key .= "1";
			$data = array_merge([
				$url_key => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '(not defined)',
			], $data);

			// Build the email message
			$mail = \Yii::$app->awsMailer->compose([
				'html' => '@common/yii/helpers/sendAlert/alertEmailHtml',
				'text' => '@common/yii/helpers/sendAlert/alertEmailText',
			], [
				'errorTitle' => $appAlert->subject,
				'errorColor' => $errorColor,
				'info' => $data,
			]);

			// Prepare email
			$mail->setSubject("Alerta: " . $appAlert->type)
				->setTo($options['destinataries'])
				->setReplyTo(['do-not-reply@cinepapaya.com' => 'DO NOT REPLY CINEPAPAYA'])
				->setFrom(['alertas@cinepapaya.com' => 'Alertas Cinepapaya']);

			// Send email
			$sent = $mail->send();

			// Save in cache to stop from sending multiple alerts in a
			// short timeframe
			if ($options['cacheDuration'] > 0)
				$cache->set($cacheKey, true, $options['cacheDuration']);

			return $sent;
		}
		catch (\Exception $ex)
		{
			@error_log("SendAlert failed " . $type . " " . $subject);
			\Yii::warning("SendAlert failed " . $type . " " . $subject . $ex->getMessage());
			return false;
		}
	}
}