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
 *
 * @package EC\EuropaWS\Exceptions
 */
class ClientInstantiationException extends Exception
{
}