<?php

namespace EC\EuropaSearch\Proxies\Converters\Components\Filters\Queries;

use EC\EuropaSearch\Messages\Components\NestedComponentInterface;
use EC\EuropaSearch\Proxies\Converters\Components\ComponentConverterInterface;

/**
 * Interface FilterQueryConverterInterface.
 *
 * Implementing this interface allows object to convert
 * "CombinedQueryInterface" components in a format used by the transportation layer.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components\Filters\Queries
 */
interface FilterQueryConverterInterface extends ComponentConverterInterface
{
    /**
     * Converts a CombinedQueryInterface component.
     *
     * @param \EC\EuropaSearch\Messages\Components\NestedComponentInterface $query
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
