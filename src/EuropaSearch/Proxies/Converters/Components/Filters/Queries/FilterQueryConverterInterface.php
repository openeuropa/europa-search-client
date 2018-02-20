<?php

namespace OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Queries;

use OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface;
use OpenEuropa\EuropaSearch\Proxies\Converters\Components\ComponentConverterInterface;

/**
 * Interface FilterQueryConverterInterface.
 *
 * Implementing this interface allows object to convert
 * "CombinedQueryInterface" components in a format used by the transportation layer.
 *
 * @package OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Queries
 */
interface FilterQueryConverterInterface extends ComponentConverterInterface
{
    /**
     * Converts a CombinedQueryInterface component.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface $query
     *   The component to convert.
     * @param array                                                         $convertedComponents
     *   The list of child components that are ready to be used in the
     *   component final conversion.
     *
     * @return array
     *   Array containing the conversion results.
     */
    public function convertComponentWithChildren(NestedComponentInterface $query, array $convertedComponents);
}
