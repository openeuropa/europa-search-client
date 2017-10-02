<?php

/**
 * @file
 * Contains EC\EuropaWS\Exceptions\ClientInstantiationException.
 */

namespace EC\EuropaWS\Exceptions;

use \Exception;

/**
 * Class ClientInstantiationException.
 *
 * This type of exceptions is catch when the service client has not been
 * instantiated correctly.
 * The exception returns 281 as code.
 *
 * @package EC\EuropaWS\Exceptions
 */
class ClientInstantiationException extends Exception
{

    /**
     * ClientInstantiationException constructor.
     *
     * @param string    $message
     *   [optional] The Exception message to throw.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct($message, 281, $previous);
    }
}
