<?php

namespace EC\EuropaSearch\Messages\Components\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FloatMetadata.
 *
 * Object for a float metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Messages\Components\DocumentMetadata
 */
class FloatMetadata extends AbstractNumericMetadata
{
    /**
     * DateMetadata constructor.
     *
     * @param string $name
     *   The raw name of the metadata.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setRawValues($values)
    {
        foreach ($values as &$value) {
            $value = floatval($value);
        }

        $this->setValues($values);
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('values', new Assert\All(['constraints' => [new Assert\Type('float')]]));
    }
}
