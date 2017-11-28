<?php

namespace EC\EuropaSearch\Exceptions;

use \Exception;

/**
 * Class ConnectionException.
 *
 * This type of exceptions is catch when the service client has not been
 * able to connect with the web service.
 * The exception returns 289 as code.
 *
 * @package EC\EuropaSearch\Exceptions
 */
class ConnectionException extends Exception
{

    /**
     * ConnectionException constructor.
     *
     * @param string    $message
     *   [optional] The Exception message to throw.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct($message, 289, $previous);
    }
}
