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
abstract class AbstractIndexingRequest extends AbstractRequest
{

    /**
     * Gets the database to send.
     *
     * @return string
     *   The database to send.
     */
    public function getDatabase()
    {
        return $this->query['database'];
    }

    /**
     * Sets the database to send.
     *
     * @param string $database
     *   The database to send.
     */
    public function setDatabase($database)
    {
        $this->query['database'] = $database;
    }

    /**
     * Gets the document identifier to send.
     *
     * @return string
     *   The document identifier to send.
     */
    public function getDocumentId()
    {
        return $this->query['reference'];
    }

    /**
     * Sets the document identifier to send.
     *
     * @param string $documentId
     *   The document identifier to send.
     */
    public function setDocumentId($documentId)
    {
        $this->query['reference'] = $documentId;
    }

    /**
     * Gets the document language to send.
     *
     * @return string
     *   The document language to send.
     */
    public function getDocumentLanguage()
    {
        return $this->query['language'];
    }

    /**
     * Sets the document language to send.
     *
     * @param string $documentLanguage
     *   The document language to send.
     */
    public function setDocumentLanguage($documentLanguage)
    {
        $this->query['language'] = $documentLanguage;
    }

    /**
     * Gets the document URI to send.
     *
     * @return string
     *   The document URI to send.
     */
    public function getDocumentURI()
    {
        return $this->query['uri'];
    }

    /**
     * Sets the document URI to send.
     *
     * @param string $documentURI
     *   The document URI to send.
     */
    public function setDocumentURI($documentURI)
    {
        $this->query['uri'] = $documentURI;
    }

    /**
     * Gets the document metadata in JSON format to send.
     *
     * @return string
     *   The document metadata in JSON format to send.
     */
    public function getMetadataJSON()
    {
        return $this->body['metadata']['contents'];
    }

    /**
     * Sets the document metadata in JSON format to send.
     *
     * @param string $metadataJSON
     *   The document metadata in JSON format to send.
     */
    public function setMetadataJSON($metadataJSON)
    {
        $this->body['metadata'] = [
            'name' => 'metadata',
            'contents' => $metadataJSON,
            'headers' => ['content-type' => 'application/json'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function addConvertedComponents(array $components)
    {
        $componentsAsObject = new \stdClass();

        array_walk($components, function ($metadataDefinition, $key, $finalObject) {
            foreach ($metadataDefinition as $name => $value) {
                $finalObject->{$name} = $value;
            }
        }, $componentsAsObject);

        $json = json_encode($componentsAsObject);
        $this->setMetadataJSON($json);
    }
}
