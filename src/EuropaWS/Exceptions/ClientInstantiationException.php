<?php

/**
 * @file
 * Contains EC\EuropaWS\Exceptions\ClientInstantiationException.
 */

namespace EC\EuropaWS\Exceptions;

use Exception;

/**
 * Class ClientInstantiationException.
 *
 * This type of exceptions is catch when the service client has not been
 * instantiated correctly.
 *
 * Its code is 281.
 *
 * @package EC\EuropaWS\Exceptions
 */
class ClientInstantiationException extends \Exception
{

    /**
     * ClientInstantiationException constructor.
     *
     * @param string    $message
     *   The exception message.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining.
     */
    public function __construct($message, Exception $previous)
    {
        parent::__construct($message, 281, $previous);
    }
}
