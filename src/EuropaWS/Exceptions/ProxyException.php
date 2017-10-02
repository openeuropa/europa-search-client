<?php

/**
 * @file
 * Contains EC\EuropaWS\Exceptions\ProxyException.
 */

namespace EC\EuropaWS\Exceptions;

use \Exception;

/**
 * Class ProxyException.
 *
 * This type of exceptions is catch when a problem occurred in the process of
 * the proxy layer.
 * The exception returns 283 as code.
 *
 * @package EC\EuropaWS\Exceptions
 */
class ProxyException extends Exception
{

    /**
     * ProxyException constructor.
     *
     * @param string    $message
     *   [optional] The Exception message to throw.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($message = "", Exception $previous = null)
    {
        parent::__construct($message, 283, $previous);
    }
}
