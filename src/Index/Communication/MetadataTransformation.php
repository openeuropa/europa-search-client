<?php
/**
 * @file
 * EC\EuropaSearch\Index\Communication\MetadataTransformation.
 */

namespace EC\EuropaSearch\Index\Communication;

use EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;

/**
 * Class MetadataTransformation.
 *
 * It is in charge of the transformation of a metadata into a format that is
 * JSON convertible.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Index\Communication
 */
class MetadataTransformation
{
    /**
     * The metadata to transform.
     *
     * @var AbstractMetadata
     */
    private $source;

    /**
     * The transformed metadata.
     *
     * @var array
     */
    private $formattedMetadata;

    /**
     * MetadataTransformation constructor.
     *
     * @param AbstractMetadata $metadata
     *   The metadata to transform.
     */
    public function __construct(AbstractMetadata $metadata)
    {
        $this->source = $metadata;

        if (!empty($metadata->getValues())) {
            $name = $metadata->getEuropaSearchName();
            $values = $this->formatMetadataValue();


            if (count($values) == 1) {
                $values = reset($values);
            }
            $this->formattedMetadata = array($name => $values);
        }
    }

    /**
     * Gets the metadata to transform.
     *
     * @return AbstractMetadata
     *   The metadata to transform.
     */
    public function getMetadateSource()
    {
        return $this->source;
    }

    /**
     * Gets the transformed metadata ready to use in a JSON encoding.
     *
     * @return array
     *   The transformed metadata where:
     *   - The key is the metadata name formatted for the Europa Search services;
     *   - The value in the right format for for the Europa Search services.
     */
    public function getTransformedMetaData()
    {
        return $this->formattedMetadata;
    }


    /**
     * Converts the metadata values to comply the Europa search services format.
     *
     * @return array
     *   The converted metadata values.
     */
    private function formatMetadataValue()
    {
        $values = $this->source->getValues();

        if ($this->source instanceof DateMetadata) {
            $values = $this->getMetadataDateValue($values);
        } elseif ($this->source instanceof BooleanMetadata) {
            $values = $this->getBooleanMetadataValue($values);
        }

        return $values;
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
