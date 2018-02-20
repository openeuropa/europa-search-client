<?php

namespace OpenEuropa\EuropaSearch\Messages\Index\Traits;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait IndexingMessageTrait.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Index\Traits
 */
trait IndexingMessageTrait
{
    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('documentURI', new Assert\NotBlank());
        $metadata->addPropertyConstraint('metadata', new Assert\NotBlank());
    }
}
