<?php

/**
 * @file
 * Contains EC\EuropaWS\Exceptions\ConnectionException.
 */

namespace EC\EuropaWS\Exceptions;

use \Exception;

/**
 * Class ConnectionException.
 *
 * This type of exceptions is catch when the service client has not been
 * able to connect with the web service.
 *
 * Its code is 289.
 *
 * @package EC\EuropaWS\Exceptions
 */
class ConnectionException extends Exception
{

    /**
     * ConnectionException constructor.
     *
     * @param string    $message
     *   The exception message.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining.
     */
    public function __construct($message, Exception $previous)
    {
        parent::__construct($message, 289, $previous);
    }
}
