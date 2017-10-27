<?php

namespace EC\EuropaSearch\Messages\Index;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractIndexingMessage.
 *
 * Extending this class allows objects to share the indexing message properties
 * that are common to all indexing request.
 *
 * @package EC\EuropaSearch\Messages\Index
 *
 * {@internal It only exists for only declaring methods common to sub-classes.}
 */
abstract class AbstractIndexingMessage extends IndexedItemDeletionMessage
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
