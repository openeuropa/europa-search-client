<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Index\DocumentMetadata\BooleanMetadataConverter.
 */

namespace EC\EuropaSearch\Proxies\Index\DocumentMetadata;

use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Proxies\ComponentConverterInterface;

/**
 * Class BooleanMetadataConverter.
 *
 * It defines the mechanism for parsing BooleanMetadata into a format that is
 * JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Index\DocumentMetadata
 */
class BooleanMetadataConverter implements ComponentConverterInterface
{

    /**
     * Converts a metadata in a JSON compatible format.
     *
     * @param ComponentInterface $metadata
     *   BooleanMetadata to convert.
     * @return array
     *   The metadata in a JSON compatible format.
     */
    public function convertComponent(ComponentInterface $metadata)
    {
        $values = $metadata->getValues();
        $name = $metadata->getEuropaSearchName();

        $values = $this->getBooleanMetadataValue($values);

        return [$name => $values];
    }

    /**
     * Gets the boolean format of metadata values.
     *
     * @param array $values
     *   The raw date values to convert.
     * @return array $finalValue
     *   The converted date values.
     */
    private function getBooleanMetadataValue($values)
    {
        $finalValue = [];
        foreach ($values as $item) {
            $finalValue[] = boolval($item);
        }

        return $finalValue;
    }
}
