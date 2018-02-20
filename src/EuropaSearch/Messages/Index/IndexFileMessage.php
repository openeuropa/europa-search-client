<?php

namespace OpenEuropa\EuropaSearch\Messages\Index;

use OpenEuropa\EuropaSearch\Messages\Index\Traits\IndexingMessageTrait;
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
 * @package OpenEuropa\EuropaSearch\Messages\Index
 */
class IndexFileMessage extends AbstractIndexingMessage
{
    use IndexingMessageTrait;

    /**
     * The content of the web content to send for indexing.
     *
     * @var string
     */
    protected $documentFile;

    /**
     * Gets the content of the indexed file.
     *
     * @return string
     *    The content of the indexed file.
     */
    public function getDocumentFile()
    {
        return $this->documentFile;
    }

    /**
     * Sets the content of the indexed file.
     *
     * @param string $documentFile
     *    The document content to index.
     */
    public function setDocumentFile($documentFile)
    {
        $this->documentFile = $documentFile;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'file';
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('documentFile', new Assert\NotBlank());
    }
}
