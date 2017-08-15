<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata.
 */

namespace EC\EuropaSearch\Messages\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IntegerMetadata.
 *
 * Object for a integer metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Messages\DocumentMetadata
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
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('values', new Assert\All(array('constraints' => array(new Assert\Type('integer'), ), )));
    }
}
