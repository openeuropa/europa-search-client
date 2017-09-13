<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Queries\FilterQueryComponentConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Queries\FilterQueryInterface;
use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class FilterQueryComponentConverter.
 *
 * Converter for AggregatedFilters object.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Queries
 */
class FilterQueryComponentConverter implements FilterQueryConverterInterface
{

    /**
     * {@inheritDoc}
     */
    public function convertComponent(ComponentInterface $component)
    {
        return $this->convertComponentWithChildren($component, []);
    }

    /**
     * {@inheritDoc}
     */
    public function convertComponentWithChildren(FilterQueryInterface $query, array $convertedComponents)
    {

        if (empty($convertedComponents)) {
            return;
        }

        $label = $query->getAggregationLabel();
        $convertedComponent = [$label => []];

        // Add only the filled components.
        foreach ($convertedComponents as $child) {
            $convertedComponent[$label][] = $child;
        }

        return $convertedComponent;
    }
}
