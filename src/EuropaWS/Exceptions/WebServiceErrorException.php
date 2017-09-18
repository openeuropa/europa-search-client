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
 * The exception returns 288 as code.
 *
 * @package EC\EuropaWS\Exceptions
 */
class WebServiceErrorException extends Exception
{

    /**
     * WebServiceErrorException constructor.
     *
     * @param string    $message
     *   [optional] The Exception message to throw.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct($message, 288, $previous);
    }
}
