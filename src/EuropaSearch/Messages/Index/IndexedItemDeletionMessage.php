<?php

namespace EC\EuropaSearch\Messages\Index;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata;
use EC\EuropaSearch\Messages\IdentifiableMessageInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IndexedItemDeletionMessage.
 *
 * It defines an item to delete in the Europa Search index.
 *
 * @package EC\EuropaSearch\Messages\Index
 */
class IndexedItemDeletionMessage implements IdentifiableMessageInterface
{

    /**
     * Prefix applicable to all converter id of classes extending this class.
     *
     * @const
     */
    const CONVERTER_NAME_PREFIX = 'europaSearch.messageProxy.indexing.';

    /**
     * The identifier common to the system and the Europa Search services.
     *
     * @var string
     */
    protected $documentId;

    /**
     * The language of the document to send for indexing.
     *
     * @var string
     */
    protected $documentLanguage;

    /**
     * The URI of the document to send for indexing.
     *
     * @var string
     */
    protected $documentURI;

    /**
     * The metadata of the document to send for indexing.
     *
     * @var array
     */
    protected $metadata = [];

    /**
     * Gets the document Id (reference).
     *
     * It is an alias for getMessageIdentifier().
     *
     * @return string
     *   The document id.
     *
     * @see getMessageIdentifier()
     */
    public function getDocumentId()
    {
        return $this->getMessageIdentifier();
    }

    /**
     * Sets the document Id (reference).
     *
     * @param string $documentId
     *   The document id.
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * Gets the document language.
     *
     * @return string
     *   The document language.
     */
    public function getDocumentLanguage()
    {
        return $this->documentLanguage;
    }

    /**
     * Sets the document language.
     *
     * @param string $documentLanguage
     *   The document language. The value must be a valid 2 characters Unicode
     *   language identifier.
     */
    public function setDocumentLanguage($documentLanguage)
    {
        $this->documentLanguage = $documentLanguage;
    }

    /**
     * Gets the document URI.
     *
     * @return string
     *   The document URI.
     */
    public function getDocumentURI()
    {
        return $this->documentURI;
    }

    /**
     * Sets the document URI.
     *
     * @param string $documentURI
     *   The document URI.
     */
    public function setDocumentURI($documentURI)
    {
        $this->documentURI = $documentURI;
    }

    /**
     * Gets the metadata of the indexed document.
     *
     * It is an alias for getComponents().
     *
     * @return array
     *    The metadata of the indexed document.
     *
     * @see getComponents()
     */
    public function getMetadataList()
    {
        return $this->getComponents();
    }

    /**
     * Adds a metadata of the indexed document.
     *
     * @param \EC\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata $metadata
     *   The metadata to add to the document.
     */
    public function addMetadata(AbstractMetadata $metadata)
    {
        $name = $metadata->getName();
        $this->metadata[$name] = $metadata;
    }

    /**
     * Remove the metadata from the indexed document metadata list.
     *
     * @param string $name
     *    The raw name of metadata to remove.
     */
    public function removeMetadata($name)
    {
        if (isset($this->metadata[$name])) {
            unset($this->metadata[$name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageIdentifier()
    {
        return $this->documentId;
    }

    /**
     * {@inheritdoc}
     */
    public function getComponents()
    {
        return $this->metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'delete.item';
    }

    /**
     * {@inheritdoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('documentId', new Assert\NotBlank());
        $metadata->addPropertyConstraint('documentLanguage', new Assert\Language());
        $metadata->addPropertyConstraints('metadata', [
            new Assert\All(['constraints' => [new Assert\Type('\EC\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata')]]),
            new Assert\Valid(['traverse' => true]),
        ]);
    }
}
