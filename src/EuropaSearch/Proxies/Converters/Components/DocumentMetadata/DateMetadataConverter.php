<?php

namespace EC\EuropaSearch\Proxies\Converters\Components\DocumentMetadata;

use EC\EuropaSearch\Proxies\Converters\Components\Utils\DateComponentConverter;
use EC\EuropaSearch\Messages\Components\ComponentInterface;
use EC\EuropaSearch\Proxies\Converters\Components\ComponentConverterInterface;

/**
 * Class DateMetadataConverter.
 *
 * It defines the mechanism for parsing DateMetadata into a format that is
 * JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Proxies\Converters\Components\DocumentMetadata
 */
class DateMetadataConverter extends DateComponentConverter implements ComponentConverterInterface
{
    /**
     * Converts a metadata in a JSON compatible format.
     *
     * @param \EC\EuropaSearch\Messages\Components\ComponentInterface $metadata
     *   DateMetadata to convert.
     * @return array
     *   The metadata in a JSON compatible format.
     */
    public function convertComponent(ComponentInterface $metadata)
    {
        $name = $metadata->getEuropaSearchName();

        $values = $metadata->getValues();
        if (!empty($values)) {
            $values = $this->getMetadataDateValues($values);
        }

        return [$name => $values];
    }

    /**
     *  Gets the date metadata values consumable by Europa Search service.
     *
     * @param array $values
     *   The raw date value to convert.
     * @return array $finalValues
     *   The converted date values.
     */
    private function getMetadataDateValues(array $values)
    {
        $finalValues = [];
        foreach ($values as $item) {
            $finalValues[] = $this->getConvertedDateValue($item);
        }

        return $finalValues;
    }
}
