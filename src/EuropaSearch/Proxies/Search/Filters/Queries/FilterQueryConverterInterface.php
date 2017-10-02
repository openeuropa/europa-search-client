<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Queries\FilterQueryConverterInterface.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Queries\FilterQueryInterface;
use EC\EuropaWS\Proxies\ComponentConverterInterface;

/**
 * Interface FilterQueryConverterInterface.
 *
 * Implementing this interface allows object to convert
 * "CombinedQueryInterface" components in a format used by the transportation layer.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Queries
 */
interface FilterQueryConverterInterface extends ComponentConverterInterface
{

    /**
     * Converts a CombinedQueryInterface component.
     *
     * @param FilterQueryInterface $query
     *   The component to convert.
     * @param array                $convertedComponents
     *   The list of child components that are ready to be used in the
     *   component final conversion.
     *
     * @return array
     *   Array containing the conversion results.
     */
    public function convertComponentWithChildren(FilterQueryInterface $query, array $convertedComponents);
}
