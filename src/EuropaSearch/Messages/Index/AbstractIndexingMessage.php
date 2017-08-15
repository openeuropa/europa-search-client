<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Index\AbstractIndexingMessage.
 */

namespace EC\EuropaSearch\Messages\Index;

use EC\EuropaSearch\Messages\DocumentMetadata\AbstractMetadata;
use EC\EuropaWS\Messages\IdentifiableMessageInterface;
use EC\EuropaWS\Proxies\ProxyProvider;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractIndexingMessage.
 *
 * Extending this class allows objects to share the indexing message properties
 * that are common to all indexing request.
 *
 * @package EC\EuropaSearch\Messages\Index
 */
abstract class AbstractIndexingMessage implements IdentifiableMessageInterface
{
    const CONVERTER_NAME_PREFIX = ProxyProvider::MESSAGE_ID_PREFIX.'indexing.';

    /**
     * The identifier common to the system and the Europa Search services.
     *
     * @var string
     */
    private $documentId;

    /**
     * The language of the document to send for indexing.
     *
     * @var string
     */
    private $documentLanguage;

    /**
     * The URI of the document to send for indexing.
     *
     * @var string
     */
    private $documentURI;

    /**
     * The metadata of the document to send for indexing.
     *
     * @var array
     */
    private $metadata = array();

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
     * @param \EC\EuropaSearch\Messages\DocumentMetadata\AbstractMetadata $metadata
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
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('documentId', new Assert\NotBlank());
        $metadata->addPropertyConstraint('documentURI', new Assert\NotBlank());
        $metadata->addPropertyConstraint('documentLanguage', new Assert\Language());
        $metadata->addPropertyConstraints('metadata', [
            new Assert\NotBlank(),
            new Assert\All(array('constraints' => array(new Assert\Type('\EC\EuropaSearch\Messages\DocumentMetadata\AbstractMetadata'), ), )),
            new Assert\Valid(array('traverse' => true)),
        ]);
    }
}
