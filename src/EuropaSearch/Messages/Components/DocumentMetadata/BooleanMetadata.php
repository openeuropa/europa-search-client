<?php

namespace EC\EuropaSearch\Messages\Components\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BooleanMetadata.
 *
 * Object for a boolean metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Messages\Components\DocumentMetadata
 */
class BooleanMetadata extends AbstractMetadata implements IndexableMetadataInterface
{

    const EUROPA_SEARCH_NAME_PREFIX = 'esBO';

    /**
     * BooleanMetadata constructor.
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
        $metadata->addPropertyConstraint('values', new Assert\All(['constraints' => [new Assert\Type('bool')]]));
    }

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function setRawValues($values)
    {
        foreach ($values as &$value) {
            $value = boolval($value);
        }

        $this->setValues($values);
    }
}
