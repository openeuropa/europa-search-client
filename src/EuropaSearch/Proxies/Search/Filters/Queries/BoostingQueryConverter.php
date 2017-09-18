<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Queries\BoostingQueryConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Queries\FilterQueryInterface;
use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class BoostingQueryConverter.
 *
 * Converter for BoostingQuery object.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Queries
 */
class BoostingQueryConverter implements FilterQueryConverterInterface
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

        $convertedComponent = ['boosting' => []];

        // Add only the filled components.
        if (!empty($convertedComponents)) {
            foreach ($convertedComponents as $child) {
                if (!empty($child)) {
                    $convertedComponent['boosting'] = array_merge($convertedComponent['boosting'], $child);
                }
            }
        }

        $boost = $query->getBoost();
        if (!is_null($boost)) {
            $boostLabel = ($boost < 0) ? 'negative_boost' : 'positive_boost';
            $convertedComponent['boosting'][$boostLabel] = abs($boost);
        }

        return $convertedComponent;
    }
}
