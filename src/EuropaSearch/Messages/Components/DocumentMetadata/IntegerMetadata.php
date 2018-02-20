<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IntegerMetadata.
 *
 * Object for a integer metadata of an indexed document.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata
 */
class IntegerMetadata extends AbstractNumericMetadata
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
            $value = intval($value);
        }

        $this->setValues($values);
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('values', new Assert\All(['constraints' => [new Assert\Type('integer')]]));
    }
}
