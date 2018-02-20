<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a full-text searchable metadata of an indexed document.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata
 */
class FullTextMetadata extends AbstractMetadata implements IndexableMetadataInterface
{
    const EUROPA_SEARCH_NAME_PREFIX = 'esIN';

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
     * Loads constraints declarations for the validator process.
     *
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('values', new Assert\All(['constraints' => [new Assert\Type('string')]]));
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'string';
    }
}
