<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Client\IndexedDocument.
 */

namespace EC\EuropaSearch\Index\Client;

use EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IndexedDocument.
 *
 * @package EC\EuropaSearch\Index\Client
 */
class IndexedDocument
{
    const WEB_CONTENT = 1;
    const BINARY = 0;

    /**
     * Document identifier.
     *
     * @var string
     */
    private $documentId;

    /**
     * Document language.
     *
     * @var string
     */
    private $documentLanguage;

    /**
     * Document type; I.E. web content or binary.
     *
     * @var int
     */
    private $documentType = IndexedDocument::WEB_CONTENT;

    /**
     * Document URI.
     *
     * @var string
     */
    private $documentURI;

    /**
     * Document metadata indexed by name.
     *
     * @var array
     *    Each value must an object of DocumentMetadata type
     */
    private $metadata = array();

    /**
     * Document content if the ; I.E. the document represent a web content.
     *
     * @var string
     */
    private $documentContent = false;

    /**
     * Gets the identifier of the indexed document.
     *
     * @return string $documentId
     *    The identifier of the indexed document.
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * Sets the identifier of the indexed document.
     *
     * @param string $documentId
     *    The identifier of the indexed document.
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * Gets the language code of the indexed document.
     *
     * @return string
     *    The language code of the indexed document.
     */
    public function getDocumentLanguage()
    {
        return $this->documentLanguage;
    }

    /**
     * Sets the language of the indexed document.
     *
     * @param string $documentLanguage
     */
    public function setDocumentLanguage($documentLanguage)
    {
        $this->documentLanguage = $documentLanguage;
    }

    /**
     * Gets the type of the indexed document.
     *
     * @return int
     *    The type of the indexed document; I.E.
     *    - WEB_CONTENT if it is web content;
     *    - BINARY if it is a file.
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Sets the type of the indexed document.
     *
     * @param int $documentType
     *    The type of the indexed document; I.E.
     *    - WEB_CONTENT if it is web content;
     *    - BINARY if it is a file.
     *    If not set, the document is considered as a web content.
     */
    public function setDocumentType($documentType = IndexedDocument::WEB_CONTENT)
    {
        $this->documentType = $documentType;
    }

    /**
     * Gets the URI of the indexed document.
     *
     * @return string
     *    The URI of the indexed document.
     */
    public function getDocumentURI()
    {
        return $this->documentURI;
    }

    /**
     * Sets the URI of the indexed document.
     *
     * @param string $documentURI
     *    The URI of the indexed document.
     */
    public function setDocumentURI($documentURI)
    {
        $this->documentURI = $documentURI;
    }

    /**
     * Gets the metadata of the indexed document.
     *
     * @return array
     *    The metadata of the indexed document.
     */
    public function getMetadataList()
    {
        return $this->metadata;
    }

    /**
     * Adds a metadata of the indexed document.
     *
     * @param \EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata $metadata
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
     * Gets a specific metadata from the indexed document metadata list.
     *
     * @param string $name
     *    The raw name of metadata to retrieve.
     * @return object|bool
     *    The metadata definition (extension of "AbstractDocumentMetadata") or
     *    false if not found.
     */
    public function getMetadata($name)
    {
        if (isset($this->metadata[$name])) {
            return $this->metadata[$name];
        }

        return false;
    }

    /**
     * Gets the content of the indexed document.
     *
     * @return string|boolean
     *    The content of the indexed document or FALSE if not set.
     */
    public function getDocumentContent()
    {
        return (!empty($this->documentContent)) ? $this->documentContent : false;
    }

    /**
     * Sets the content of the indexed document.
     *
     * If the document to index is not a web content, this attribute is not
     * to set.
     *
     * @param string $documentContent
     *    The document content to index.
     */
    public function setDocumentContent($documentContent)
    {
        $this->documentContent = $documentContent;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('documentId', new Assert\NotBlank());
        $metadata->addPropertyConstraint('documentURI', new Assert\NotBlank());
        $metadata->addPropertyConstraint('documentType', new Assert\Type('int'));
        $metadata->addPropertyConstraint('documentLanguage', new Assert\Language());
        $metadata->addPropertyConstraints('metadata', [
            new Assert\NotBlank(),
            new Assert\All(array('constraints' => array(new Assert\Type('\EC\EuropaSearch\Common\DocumentMetadata\AbstractMetadata'), ), )),
            new Assert\Valid(array('traverse' => true)),
        ]);

        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for documentContent.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if ($this->getDocumentType() == IndexedDocument::WEB_CONTENT && empty($this->getDocumentContent())) {
            $context->buildViolation('The documentContent should not be empty as the indexed document is a web content.')
                ->atPath('documentContent')
                ->addViolation();
        }

        if ($this->getDocumentType() == IndexedDocument::BINARY && !empty($this->getDocumentContent())) {
            $context->buildViolation('The documentContent should be empty as the indexed document is a file.')
                ->atPath('documentContent')
                ->addViolation();
        }
    }
}
