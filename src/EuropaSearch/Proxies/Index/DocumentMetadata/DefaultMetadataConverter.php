<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Index\DocumentMetadata\DefaultMetadataConverter.
 */

namespace EC\EuropaSearch\Proxies\Index\DocumentMetadata;

use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Proxies\ComponentConverterInterface;

/**
 * Class DefaultMetadataConverter.
 *
 * It defines the default mechanism for parsing metadata into a format that is
 * JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Index\DocumentMetadata
 */
class DefaultMetadataConverter implements ComponentConverterInterface
{

    /**
     * Converts a metadata in a JSON compatible format.
     *
     * @param ComponentInterface $metadata
     *   AbstractMetadata to convert.
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
