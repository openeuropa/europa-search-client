<?php

/**
 * @file
 * EC\EuropaSearch\Index\Communication\Parsers\DateMetadataParser.
 */

namespace EC\EuropaSearch\Index\Communication\Parsers;

use EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata;

/**
 * Class DateMetadataParser.
 *
 * It defines the mechanism for parsing DateMetadata into a format that is
 * JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Index\Communication\Parsers
 */
class DateMetadataParser implements MetadataParserInterface
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

        $values = $this->getMetadataDateValue($values);

        if (count($values) <= 1) {
            $values = reset($values);
        }

        return array($name => $values);
    }

    /**
     *  Gets the date metadata values consumable by Europa Search service.
     *
     * @param array $values
     *   The raw date value to convert.
     * @return array $finalValues
     *   The converted date values.
     */
    private function getMetadataDateValue($values)
    {
        $finalValues = array();
        foreach ($values as $item) {
            $dateTime = new \DateTime($item);
            $finalValues[] = $dateTime->format(\DateTime::ISO8601);
        }

        return $finalValues;
    }
}
