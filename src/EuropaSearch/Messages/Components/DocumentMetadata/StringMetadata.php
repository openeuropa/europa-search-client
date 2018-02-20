<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class StringMetadata.
 *
 * Object for a string metadata of an indexed document.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata
 */
class StringMetadata extends AbstractMetadata implements IndexableMetadataInterface
{
    const EUROPA_SEARCH_NAME_PREFIX = 'esST';

    /**
     * StringMetadata constructor.
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
        $metadata->addPropertyConstraint('values', new Assert\All(['constraints' => [new Assert\Type('string')]]));
    }

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'string';
    }
}
