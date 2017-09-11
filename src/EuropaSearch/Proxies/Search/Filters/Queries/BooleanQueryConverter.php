<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Queries\BooleanQueryConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Queries\FilterQueryInterface;
use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class BooleanQueryConverter.
 *
 * Converter for BooleanQuery object.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Queries
 */
class BooleanQueryConverter implements FilterQueryConverterInterface
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
        $convertedComponent = ['bool' => []];

        // Add only the filled components.
        if (!empty($convertedComponents)) {
            foreach ($convertedComponents as $child) {
                if (!empty($child)) {
                    $convertedComponent['bool'] = array_merge($convertedComponent['bool'], $child);
                }
            }
        }



        if (!is_null($query->getBoost())) {
            $convertedComponent['bool']['boost'] = $query->getBoost();
        }

        return $convertedComponent;
    }
}
