<?php
namespace common\yii\helpers;

use yii\swiftmailer\Mailer as SwiftMailer;
use yii\helpers\StringHelper;

class Mailer extends SwiftMailer
{
	public $allEmailsTo = null;
	
	public $allEmailsDomain = '@cinepapaya.com';
	
	/**
	 * Override so we can send real emails but all emails
	 * are sent only to the allEmailsDomain or if not found
	 * then to the allEmailsTo
	 * 
	 * allEmailsDomain only works when allEmailsTo is a valid email
	 * 
	 * (non-PHPdoc)
	 * @see \yii\mail\BaseMailer::send()
	 */
	public function send($message)
	{
		if (!empty($this->allEmailsTo))
		{
			$to = $message->getTo();
			if (!is_array($to))
				$to = [$to => $to];
			$to = array_keys($to);
			$destinataries = [];
			if (!empty($this->allEmailsDomain))
			{
				foreach ($to as $email)
				{
					if (StringHelper::endsWith(strtolower($email), strtolower($this->allEmailsDomain)))
						$destinataries[] = $email;
				}
			}
			$destinataries[$this->allEmailsTo] = $this->allEmailsTo;
			$message->setTo($destinataries); 
			$message->setSubject($message->getSubject() . " (ORIGINALLY TO: ". implode(", ", $to) .")");
		}
		if (YII_ENV != 'prod')
			$message->setSubject(strtoupper(YII_ENV) . ": " . $message->getSubject());
		return parent::send($message);
	}
}