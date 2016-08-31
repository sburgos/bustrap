<?php
namespace common\yii\helpers;

class SiftScienceHelper
{
	const TIMEOUT = 8;
	
	public static function getJavascriptKey()
	{
		return YII_ENV == 'prod' ? '8118cf3497' : '73e8b75f37';
	}
	
	public static function getRestKey()
	{
		return YII_ENV == 'prod' ? '3d672e3f8382448f' : '81318e1a5de60438';
	}
	
	/**
	 * When a user creates an account.
	 * 
	 * @param \orm\v1\identity\User $user
	 * @param string $sessionId
	 * @param string $socialType
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function userCreatedAccount($user, $sessionId, $socialType = null)
	{
		if ($user === null)
			return false;
	
		try {
			$client = new \SiftClient(static::getRestKey());
			$properties = [
				'$user_id' => trim(strtolower($user->email)),
				'$session_id' => $sessionId,
				'$user_email' => $user->email,
				'$name' => $user->firstName . " " . $user->lastName,
			];
			if (in_array($socialType, ['facebook', 'twitter', 'yahoo', 'google']))
				$properties['$social_sign_on_type'] = '$' . $socialType;
			else if ($socialType !== null)
				$properties['$social_sign_on_type'] = '$other';
				
			$response = $client->track('$create_account', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science create account failed',
				'user' => $user->id,
				'email' => $user->email,
				'socialType' => $socialType,
				'sessionId' => $sessionId,
			]);
		}
	}
	
	/**
	 * When a user updates his account information
	 * 
	 * @param \orm\v1\identity\User $user
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function userUpdatedAccount($user)
	{
		if ($user === null)
			return false;
		
		try {
			$client = new \SiftClient(static::getRestKey());
			$properties = [
				'$user_id' => trim(strtolower($user->email)),
				'$user_email' => $user->email,
				'$changed_password' => false,
				'$name' => $user->firstName . " " . $user->lastName,
			];
			
			$response = $client->track('$update_account', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science update account failed',
				'user' => $user->id,
				'email' => $user->email,
			]);
		}
	}
	
	/**
	 * Whenever a user updates his account's password
	 * 
	 * @param \orm\v1\identity\User $user
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function userChangedPassword($user)
	{
		if ($user === null)
			return false;
		
		try {
			$client = new \SiftClient(static::getRestKey());
			$properties = [
				'$user_id' => trim(strtolower($user->email)),
				'$user_email' => $user->email,
				'$changed_password' => true,
				'$name' => $user->firstName . " " . $user->lastName,
			];
			
			$response = $client->track('$update_account', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science change account password failed',
				'user' => $user->id,
				'email' => $user->email,
			]);
		}
	}
	
	/**
	 * Whenever a user changes his email
	 * 
	 * @param \orm\v1\identity\User $user
	 * @param string $oldEmail
	 * @param string $newEmail
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function userChangedEmail($user, $oldEmail, $newEmail)
	{
		if ($user === null)
			return false;
		
		try {
			$client = new \SiftClient(static::getRestKey());
			$properties = [
				'$user_id' => trim(strtolower($oldEmail)),
				'$user_email' => $newEmail,
				'$changed_password' => false,
				'$name' => $user->firstName . " " . $user->lastName,
			];
			
			$response = $client->track('$update_account', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science change account email failed',
				'user' => $user->id,
				'oldEmail' => $oldEmail,
				'newEmail' => $newEmail,
			]);
		}
	}
	
	/**
	 * Get a users risk score and label
	 * 
	 * Returns a \stdClass object with a score (0-100) and a label (null, 'bad', 'good')
	 * 
	 * @param string $userEmail
	 * @return \stdClass|NULL
	 */
	public static function getUsersScore($userEmail)
	{
		try {
			$client = new \SiftClient(static::getRestKey());
			$response = $client->score(trim(strtolower($userEmail)), static::TIMEOUT);
			if ($response->isOk())
			{
				$obj = new \stdClass();
				$obj->score = round($response->body['score'] * 100, 4);
				$obj->label = null;
				if (isset($response->body['latest_label']['is_bad']))
				{
					if ($response->body['latest_label']['is_bad'] === true)
						$obj->label = 'bad';
					else
						$obj->label = 'good';
				}
				return $obj;
			}
			return null;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science get users score failed',
				'email' => $userEmail,
			]);
		}
		return null;
	}
	
	/**
	 * Create a Sift Science order
	 * 
	 * @param \orm\v1\pay\Ticket $ticket
	 * @param string $sessionId
	 * @param array|null $customFields
	 * @return boolean|unknown
	 */
	public static function createOrder($ticket, $sessionId = '', $customFields = [])
	{
		if ($ticket === null)
			return false;
		
		try {
			$ticketAmountInfo = $ticket->ticketAmountInfo;
			$ticketCustomerInfo = $ticket->ticketCustomerInfo;
			
			$client = new \SiftClient(static::getRestKey());
			$amount = $ticketAmountInfo->grandTotal * 10000;
			if (strtoupper($ticketAmountInfo->currencyId) != 'CLP')
				$amount *= 100;
			
			// Calculate minutes to event
			$minsToEvent = 0;
			$eventWeekDay = '';
			$eventMinutesFromDayStart = 0;
			try {
				$eventTimezone = $ticket->timeZoneId;
				$eventInLocalTZ = new \DateTime($ticket->eventDate, new \DateTimeZone($eventTimezone));
				$nowInLocalTZ = new \DateTime(null, new \DateTimeZone($eventTimezone));
				$diffToEvent = $eventInLocalTZ->diff($nowInLocalTZ, false);
				$minsToEvent = $diffToEvent->days * 24 * 60 + $diffToEvent->h * 60 + $diffToEvent->i;
				$eventWeekDay = $eventInLocalTZ->format('D');
				$eventMinutesFromDayStart = $eventInLocalTZ->format('H') * 60 + $eventInLocalTZ->format('i');
			}
			catch (\Exception $innerEx){
				SendAlert::handledException($innerEx, [
					'ticket' => $ticket->id,
					'timeZoneId' => $ticket->timeZoneId,
					'eventDate' => $ticket->eventDate,
				]);
				$minsToEvent = 0;
			}
			
			// Calculate items
			$items = [];
			foreach ($ticket->ticketItems as $item)
			{
				$price = $item->value * 10000;
				if (strtoupper($ticketAmountInfo->currencyId) != 'CLP')
					$price *= 100;
				$items[] = [
					'$item_id' => $item->rowNumber,
					'$product_title' => $item->name,
					'$price' => $price,
					'$currency_code' => strtoupper($ticketAmountInfo->currencyId),
					'$quantity' => 1,
					'$category' => $item->type,
				];
			}

			// @TODO Get the theater name for other apps
			$theaterName = "";
			if ($ticket->appId == 'cine')
			{
				$theaterName = $ticket->ticketCineInfo->movieTheater->name;
			}
			
			// Build the properties
			$properties = [
				'$user_id' => trim(strtolower($ticketCustomerInfo->customerEmail)),
				'$session_id' => $sessionId,
				'$order_id' => $ticket->id,
				'$user_email' => $ticketCustomerInfo->customerEmail,
				'$amount' => $amount,
				'$currency_code' => strtoupper($ticketAmountInfo->currencyId),
				'$items' => $items,
				'theaterName' => $theaterName,
				'minutesToEvent' => $minsToEvent,
				'isGuest' => $ticketCustomerInfo->isGuest,
				'eventWeekDay' => $eventWeekDay,
				'eventTimeOfDayMinutes' => $eventMinutesFromDayStart,
				'countryCode' => $ticket->countryId,
				'platform' => $ticket->restClientId,
			];
			
			// Attach custom fields
			if (is_array($customFields))
			{
				foreach ($customFields as $key => $val)
					$properties[$key] = $val;
			}
				
			$response = $client->track('$create_order', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, ['ticket'=>$ticket->id]);
		}
	}
	
	/**
	 * Register a transaction. $siftProperties must be an array with the
	 * following fields:
	 * 
	 *  '$session_id' => '',
	 *  '$transaction_type' => '',
	 *  '$transaction_status' => '',
	 *  '$transaction_id' => '',
	 *  '$payment_method' => [
	 *  	'$payment_type' => '',
	 *  	'$payment_gateway' => '',
	 *  	'$card_bin' => '',
	 *  	'$card_last4' => '',
	 *  ],
	 *  
	 * @see https://siftscience.com/resources/references/events-api.html#event-transaction
	 *  
	 * @param \orm\v1\pay\Ticket $ticket
	 * @param array $siftProperties
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function registerTransaction($ticket, $siftProperties)
	{
		if ($ticket === null)
			return false;
		
		try {
			$ticketAmountInfo = $ticket->ticketAmountInfo;
			$ticketCustomerInfo = $ticket->ticketCustomerInfo;
			
			$client = new \SiftClient(static::getRestKey());
			$amount = $ticketAmountInfo->grandTotal * 10000;
			if (strtoupper($ticketAmountInfo->currencyId) != 'CLP')
				$amount *= 100;
			
			$client = new \SiftClient(static::getRestKey());
			$siftProperties['$user_id'] = trim(strtolower($ticketCustomerInfo->customerEmail));
			$siftProperties['$user_email'] = $ticketCustomerInfo->customerEmail;
			$siftProperties['$order_id'] = $ticket->id;
			$siftProperties['$amount'] = $amount;
			$siftProperties['$currency_code'] = $ticketAmountInfo->currencyId;
			$response = $client->track('$transaction', $siftProperties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science register transaction failed',
				'ticket'=>$ticket->id,
				'siftProperties' => $siftProperties,
			]);
		}
	} 
	
	/**
	 * Link a session to a user. This should be called whenever a user's session
	 * id is about to change. For example on web after login the session id changes
	 * so just before changing this session id you should call this method.
	 * 
	 * @param string $sessionId
	 * @param \orm\v1\identity\User $user
	 * @param boolean $success
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function linkSessionToUser($sessionId, $user, $success = true)
	{
		if ($user === null)
			return false;
	
		try {
			$client = new \SiftClient(static::getRestKey());
			$properties = [
				'$user_id' => trim(strtolower($user->email)),
				'$session_id' => $sessionId,
			];
			$response = $client->track('$link_session_to_user', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science link session to user failed',
				'sessionId' => $sessionId,
				'user' => $user->id,
			]);
		}
	}
	
	/**
	 * When a users logs in
	 * 
	 * @param \orm\v1\identity\User $user
	 * @param string $sessionId
	 * @param boolena $success
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function trackLogin($user, $sessionId, $success = true)
	{
		if ($user === null)
			return false;
		
		try {
			$client = new \SiftClient(static::getRestKey());
			$properties = [
				'$user_id' => trim(strtolower($user->email)),
				'$session_id' => $sessionId,
				'$login_status' => $success ? '$success' : '$failure',
			];
			$response = $client->track('$login', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science track login failed',
				'sessionId' => $sessionId,
				'user' => $user->id,
				'success' => $success,
			]);
		}
	}
	
	/**
	 * When a user logs out
	 * 
	 * @param \orm\v1\identity\User $user
	 * @return boolean|Ambigous <NULL, SiftResponse>
	 */
	public static function trackLogout($userEmail)
	{
		if ($user === null)
			return false;
	
		try {
			$client = new \SiftClient(static::getRestKey());
			$properties = [
				'$user_id' => trim(strtolower($userEmail)),
			];
			$response = $client->track('$logout', $properties, static::TIMEOUT);
			return $response;
		}
		catch (\Exception $ex) {
			SendAlert::handledException($ex, [
				'msg' => 'Sift science track logout failed',
				'userEmail' => $userEmail,
			]);
		}
	}
	
	/**
	 * Return the javascript snippet to put on the website. You must provide
	 * a sessionId (Same as the one sending as Fingerprint to the REST API) and
	 * the user's email if you have it (if not just send null).
	 * 
	 * @param string $sessionId
	 * @param string|null $userEmail
	 * @return string
	 */
	public static function getJSSnippet($sessionId, $userEmail = null)
	{
		$jsKey = static::getJavascriptKey();
		if (!$jsKey)
			return '';
		
		$userId = '';
		if (!empty($userEmail))
			$userId = trim(strtolower($userEmail));
		
		$sessionId = trim($sessionId);
		
		return "var _sift = _sift || [];"
			. "_sift.push(['_setAccount', '{$jsKey}']);"
			. "_sift.push(['_setUserId', '{$userId}']);"
			. "_sift.push(['_setSessionId', '{$sessionId}']);"
			. "_sift.push(['_trackPageview']);"
			. "(function() { "
			. "function ls() { "
			. "  var e = document.createElement('script'); "
			. "  e.type = 'text/javascript'; "
			. "  e.async = true; "
			. "  e.src = ('https:' === document.location.protocol ? 'https://' : 'http://') + 'cdn.siftscience.com/s.js'; "
			. "  var s = document.getElementsByTagName('script')[0]; "
			. "  s.parentNode.insertBefore(e, s); "
			. "} "
			. "if (window.attachEvent) { "
			. "  window.attachEvent('onload', ls); "
			. "} else { "
			. "  window.addEventListener('load', ls, false); "
			. "} "
			. "}());"
		;
	}
}