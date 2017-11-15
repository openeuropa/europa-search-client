<?php

namespace EC\EuropaSearch\Messages\Index;

use EC\EuropaSearch\Messages\Index\Traits\IndexingMessageTrait;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IndexWebContentMessage.
 *
 * It defines a web content document that is sent for indexing to the Europa Search
 * services.
 *
 * @package EC\EuropaSearch\Messages\Index
 */
class IndexWebContentMessage extends AbstractIndexingMessage
{
    use IndexingMessageTrait;

    /**
     * The content of the web content to send for indexing.
     *
     * @var string
     */
    protected $documentContent;

    /**
     * Gets the content of the indexed document.
     *
     * @return string
     *    The content of the indexed document.
     */
    public function getDocumentContent()
    {
        return $this->documentContent;
    }

    /**
     * Sets the content of the indexed document.
     *
     * @param string $documentContent
     *    The document content to index.
     */
    public function setDocumentContent($documentContent)
    {
        $this->documentContent = $documentContent;
    }

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'webContent';
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('documentContent', new Assert\NotBlank());
    }
}
