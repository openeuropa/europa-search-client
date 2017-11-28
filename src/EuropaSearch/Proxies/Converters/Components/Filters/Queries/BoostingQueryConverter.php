<?php

namespace EC\EuropaSearch\Proxies\Converters\Components\Filters\Queries;

use EC\EuropaSearch\Messages\Components\NestedComponentInterface;
use EC\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Class BoostingQueryConverter.
 *
 * Converter for BoostingQuery object.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components\Filters\Queries
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
    public function convertComponentWithChildren(NestedComponentInterface $query, array $convertedComponents)
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
