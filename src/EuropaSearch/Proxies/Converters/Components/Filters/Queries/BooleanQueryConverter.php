<?php

namespace OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Queries;

use OpenEuropa\EuropaSearch\Messages\Components\ComponentInterface;
use OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface;

/**
 * Class BooleanQueryConverter.
 *
 * Converter for BooleanQuery object.
 *
 * @package OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Queries
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
    public function convertComponentWithChildren(NestedComponentInterface $query, array $convertedComponents)
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
