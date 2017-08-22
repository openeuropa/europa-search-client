<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Combined\BoostingQueryConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\CombinedQueryInterface;
use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class BoostingQueryConverter.
 *
 * Converter for BoostingQuery object.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Combined
 */
class BoostingQueryConverter implements CombinedQueryConverterInterface
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
    public function convertComponentWithChildren(CombinedQueryInterface $query, array $convertedComponents)
    {

        $convertedComponent = ['boosting' => []];

        // Add only the filled components.
        if (!empty($convertedComponents)) {
            foreach ($convertedComponents as $child) {
                $convertedComponent['boosting'] = array_merge($convertedComponent['boosting'], $child);
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