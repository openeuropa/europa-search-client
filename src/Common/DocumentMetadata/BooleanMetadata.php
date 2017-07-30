<?php
/**
 * @file
 * Contains EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata.
 */

namespace EC\EuropaSearch\Common\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a boolean metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class BooleanMetadata extends AbstractMetadata
{
    const TYPE = 'boolean';

    /**
     * BooleanMetadata constructor.
     *
     * @param string $name
     *   The raw name of the metadata.
     * @param array  $values
     *   The values of the metadata.
     *   If it is an array, it must contains boolean items only.
     */
    public function __construct($name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
        $this->type = self::TYPE;
    }

    /**
     * @inheritdoc
     *
     * @return string $type
     *   - 'boolean': for boolean that can be used to filter a search.
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('values', new Assert\All(array('constraints' => array(new Assert\Type('bool'), ), )));
    }

    /**
     * @inheritdoc
     *
     * @return array
     *   The converted metadata where:
     *   - The key is the metadata name formatted for the Europa Search services;
     *   - The value in the right format for for the Europa Search services.
     */
    public function encodeMetadata()
    {
        $name = $this->getEuropaSearchName();
        $values = $this->encodeMetadataBooleanValue($this->values);
        if (count($values) == 1) {
            $values = reset($values);
        }

        return array($name => $values);
    }

    /**
     * @inheritdoc
     *
     * @return string
     *   The final name.
     */
    public function getEuropaSearchName()
    {
        return 'esBO_'.$this->name;
    }

    /**
     * Gets the boolean metadata value consumable by Europa Search service.
     *
     * @param array $values
     *   The raw date values to convert.
     * @return array $finalValue
     *   The converted date values.
     */
    private function encodeMetadataBooleanValue($values)
    {
        $finalValue = array();
        foreach ($values as $item) {
            $finalValue[] = boolval($item);
        }

        return $finalValue;
    }
}
