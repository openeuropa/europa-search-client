<?php

namespace EC\EuropaSearch\Messages\Index;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IndexFileMessage.
 *
 * Represents a File (Binary) that must be indexed via the
 * Europa Search services.
 *
 * @todo Defining completely when files will be supported by the client
 * (treated in another comming issue).
 *
 * @package EC\EuropaSearch\Messages\Index
 */
class IndexFileMessage extends AbstractIndexingMessage
{
    use IndexingMessageTrait;

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        // TODO: Implement getConverterIdentifier() method.
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        // TODO: Implement getConstraints() method.
    }
}
