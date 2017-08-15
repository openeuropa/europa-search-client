<?php

/**
 * @file
 * Contains EC\EuropaWS\Exceptions\WebServiceErrorException.
 */

namespace EC\EuropaWS\Exceptions;

use \Exception;

/**
 * Class WebServiceErrorException.
 *
 * This type of exceptions is catch when the web service call returns an error.
 *
 * Its code is 288.
 *
 * @package EC\EuropaWS\Exceptions
 */
class WebServiceErrorException extends Exception
{

    /**
     * WebServiceErrorException constructor.
     *
     * @param string    $message
     *   The exception message.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining.
     */
    public function __construct($message, Exception $previous)
    {
        parent::__construct($message, 288, $previous);
    }
}
