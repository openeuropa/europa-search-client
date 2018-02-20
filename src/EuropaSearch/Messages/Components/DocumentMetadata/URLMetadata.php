<?php

namespace OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class URLMetadata.
 *
 * Object for a URL metadata of an indexed document.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata
 */
class URLMetadata extends StringMetadata implements IndexableMetadataInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('values', new Assert\All(['constraints' => [new Assert\Url()]]));
    }
}
