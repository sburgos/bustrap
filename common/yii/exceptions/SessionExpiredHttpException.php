<?php

namespace common\yii\exceptions;

use yii\web\HttpException;
use yii\web\Response;

/**
 * Class SessionExpiredHttpException
 *
 * User's AccessToken has expired.
 * Status code 480.
 *
 * @package common\yii\exceptions
 */
class SessionExpiredHttpException extends HttpException
{
    /**
     * Constructor.
     * @param string $message error message
     * @param integer $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        if ( ! isset(Response::$httpStatuses['480']))
        {
            Response::$httpStatuses['480'] = $this->getName();
        }
        parent::__construct(480, $message ? : 'Your session has expired, please log in again.', $code, $previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Session Expired';
    }
}