<?php

/**
 * @file
 * EC\EuropaSearch\Index\Communication\Parsers\DefaultMetadataParser.
 */

namespace EC\EuropaSearch\Index\Communication\Parsers;

use EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata;

/**
 * Abstract Class DefaultMetadataParser.
 *
 * It defines the default mechanism for parsing metadata into a format that is
 * JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Index\Communication\Parsers
 */
class DefaultMetadataParser implements MetadataParserInterface
{
    /**
     * @inheritdoc
     *
     * @param \EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata $metadata
     *   The metadata object to parse.
     *
     * @return array
     *   The converted metadata values where:
     *   - The key is the metadata name in Europa Search format.
     *   - The value is the metadata value compliant with the JSON format.
     */
    public function parse(AbstractMetadata $metadata)
    {
        $values = $metadata->getValues();
        $name = $metadata->getEuropaSearchName();

        if (count($values) <= 1) {
            $values = reset($values);
        }

        return array($name => $values);
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
        $finalValue = array();
        foreach ($values as $item) {
            $finalValue[] = boolval($item);
        }

        return $finalValue;
    }
}
