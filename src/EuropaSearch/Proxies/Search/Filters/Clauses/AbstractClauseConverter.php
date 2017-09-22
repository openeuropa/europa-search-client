<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Clauses\
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Clauses;

use EC\EuropaSearch\Proxies\Utils\DateComponentConverter;
use EC\EuropaWS\Proxies\ComponentConverterInterface;

/**
 * Class AbstractClauseConverter.
 *
 * Defines common methods for Simple filter objects.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Clauses
 */
abstract class AbstractClauseConverter extends DateComponentConverter implements ComponentConverterInterface
{


}
