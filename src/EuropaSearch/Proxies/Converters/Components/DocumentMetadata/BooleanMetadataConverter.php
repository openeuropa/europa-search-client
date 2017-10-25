<?php

namespace EC\EuropaSearch\Proxies\Converters\Components\DocumentMetadata;

use EC\EuropaSearch\Messages\Components\ComponentInterface;
use EC\EuropaSearch\Proxies\Converters\Components\ComponentConverterInterface;

/**
 * Class BooleanMetadataConverter.
 *
 * It defines the mechanism for parsing BooleanMetadata into a format that is
 * JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components\DocumentMetadata
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

        return [$name => $values];
    }
}
