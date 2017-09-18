<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Clauses\
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Clauses;

use EC\EuropaWS\Proxies\ComponentConverterInterface;

/**
 * Class AbstractClauseConverter.
 *
 * Defines common methods for Simple filter objects.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Clauses
 */
abstract class AbstractClauseConverter implements ComponentConverterInterface
{

    /**
     *  Gets the date value consumable by Europa Search service.
     *
     * @param mixed $value
     *   The raw date value to convert.
     * @return mixed $finalValue
     *   The converted date values.
     */
    protected function getConvertedDateValue($value)
    {

        $dateTime = new \DateTime($value);

        return $dateTime->format(\DateTime::ISO8601);
    }
}
