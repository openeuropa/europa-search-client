<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Exception;

use Throwable;

/**
 * Thrown when an API call returns a status code other than 200.
 */
class EuropaSearchApiInvalidStatusCodeException extends \RuntimeException
{

}
