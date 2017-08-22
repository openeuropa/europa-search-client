<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Combined\BooleanQueryConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\CombinedQueryInterface;
use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class BooleanQueryConverter.
 *
 * Converter for BooleanQuery object.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Combined
 */
class BooleanQueryConverter implements CombinedQueryConverterInterface
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
        $convertedComponent = ['bool' => []];

        // Add only the filled components.
        if (!empty($convertedComponents)) {
            foreach ($convertedComponents as $child) {
                $convertedComponent['bool'] = array_merge($convertedComponent['bool'], $child);
            }
        }



        if (!is_null($query->getBoost())) {
            $convertedComponent['bool']['boost'] = $query->getBoost();
        }

        return $convertedComponent;
    }
}