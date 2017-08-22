<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Combined\CombinedQueryConverterInterface.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\CombinedQueryInterface;
use EC\EuropaWS\Proxies\ComponentConverterInterface;

/**
 * Interface CombinedQueryConverterInterface.
 *
 * Implementing this interface allows object to convert
 * "CombinedQueryInterface" components in a format used by the transportation layer.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Combined
 */
interface CombinedQueryConverterInterface extends ComponentConverterInterface
{

    /**
     * Converts a CombinedQueryInterface component.
     *
     * @param CombinedQueryInterface $query
     *   The component to convert.
     * @param array                  $convertedComponents
     *   The list of child components that are ready to be used in the
     *   component final conversion.
     *
     * @return array
     *   Array containing the conversion results.
     */
    public function convertComponentWithChildren(CombinedQueryInterface $query, array $convertedComponents);
}
