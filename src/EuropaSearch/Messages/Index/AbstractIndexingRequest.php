<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Index\AbstractIndexingRequest.
 */

namespace EC\EuropaSearch\Messages\Index;

use EC\EuropaSearch\Messages\AbstractRequest;

/**
 * Class AbstractIndexingRequest.
 *
 * It represent a generic indexing request content sent to the
 * Index Europa Search service.
 *
 * @package EC\EuropaSearch\Messages\Index
 */
class AbstractIndexingRequest extends AbstractRequest
{

    /**
     * The database to send with the request.
     *
     * @var string
     */
    private $database;

    /**
     * The document identifier to send with the request.
     *
     * @var string
     */
    private $documentId;

    /**
     * The document language to send with the request.
     *
     * @var string
     */
    private $documentLanguage;

    /**
     * The document URI to send with the request.
     *
     * @var string
     */
    private $documentURI;

    /**
     * The document metadata in JSON format to send with the request.
     *
     * @var string
     */
    private $metadataJSON;

    /**
     * Gets the database to send.
     *
     * @return string
     *   The database to send.
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Sets the database to send.
     *
     * @param string $database
     *   The database to send.
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * Gets the document identifier to send.
     *
     * @return string
     *   The document identifier to send.
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * Sets the document identifier to send.
     *
     * @param string $documentId
     *   The document identifier to send.
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;
    }

    /**
     * Gets the document language to send.
     *
     * @return string
     *   The document language to send.
     */
    public function getDocumentLanguage()
    {
        return $this->documentLanguage;
    }

    /**
     * Sets the document language to send.
     *
     * @param string $documentLanguage
     *   The document language to send.
     */
    public function setDocumentLanguage($documentLanguage)
    {
        $this->documentLanguage = $documentLanguage;
    }

    /**
     * Gets the document URI to send.
     *
     * @return string
     *   The document URI to send.
     */
    public function getDocumentURI()
    {
        return $this->documentURI;
    }

    /**
     * Sets the document URI to send.
     *
     * @param string $documentURI
     *   The document URI to send.
     */
    public function setDocumentURI($documentURI)
    {
        $this->documentURI = $documentURI;
    }

    /**
     * Gets the document metadata in JSON format to send.
     *
     * @return string
     *   The document metadata in JSON format to send.
     */
    public function getMetadataJSON()
    {
        return $this->metadataJSON;
    }

    /**
     * Sets the document metadata in JSON format to send.
     *
     * @param string $metadataJSON
     *   The document metadata in JSON format to send.
     */
    public function setMetadataJSON($metadataJSON)
    {
        $this->metadataJSON = $metadataJSON;
    }

    /**
     * {@inheritDoc}
     */
    public function addConvertedComponents(array $components)
    {
        $json = json_encode($components);
        $this->setMetadataJSON($json);
    }
}
