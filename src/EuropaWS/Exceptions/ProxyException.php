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
 *
 * Its code is 283.
 *
 * @package EC\EuropaWS\Exceptions
 */
class ProxyException extends Exception
{

    /**
     * ProxyException constructor.
     *
     * @param string    $message
     *   The exception message.
     * @param Exception $previous
     *   [optional] The previous exception used for the exception chaining.
     */
    public function __construct($message, Exception $previous)
    {
        parent::__construct($message, 283, $previous);
    }
}
