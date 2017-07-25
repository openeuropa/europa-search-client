<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\IndexClient.
 */

namespace EC\EuropaSearch\Index\Client;

use EC\EuropaSearch\Common\Exceptions\CommunicationException;
use EC\EuropaSearch\Common\Exceptions\ConversionException;
use EC\EuropaSearch\Common\Exceptions\ValidationException;
use EC\EuropaSearch\Common\Exceptions\ConfigurationException;
use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Index\IndexServiceContainer;

/**
 * Class IndexClient
 *
 * It manages client index requests for the Europa search services.
 *
 * @package EC\EuropaSearch\Index
 */
class IndexClient
{
    private $container;

    /**
     * IndexClient constructor.
     *
     * @param ServiceConfiguration $serviceConfiguration
     *    The client configuration with the service connection parameters.
     *
     * @throws ConfigurationException
     *    In case of service settings badly configured
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->container = new IndexServiceContainer($serviceConfiguration);
        $validationErrors = $this->container->get('validator')->validate($serviceConfiguration);
        if (count($validationErrors) > 0) {
            $exception = new ConfigurationException('The service configuration is invalid');
            $exception->setErrorList($validationErrors);
            throw $exception;
        }
    }

    /**
     * Sends a request for indexing a document or update its index reference.
     *
     * This document can be a wbe content or a file
     *
     * @param IndexedDocument $indexedDocument
     *    The document to index.
     *
     * @throws ValidationException
     *    Raised if the IndexedDocument is not correctly defined
     * @throws CommunicationException
     *    Raised if the IndexedDocument has not been sent to the services.
     * @throws ConversionException
     *    Raised if the IndexedDocument processing before sending to the
     *    services failed.
     */
    public function sendIndexingRequest(IndexedDocument $indexedDocument)
    {
        $validationErrors = $this->container->get('validator')->validate($indexedDocument);
        if (count($validationErrors) > 0) {
            $exception = new ValidationException('The indexed document object is not correctly defined');
            $exception->setErrorList($validationErrors);
            throw $exception;
        }
        $this->container->get('communicator')->communicateContentIndexing($indexedDocument);
    }

    /**
     * Sends a request to remove a document from the index.
     *
     * @param string $documentId
     *   The reference of the document to remove.
     *
     * @throws ValidationException
     *    Raised if the IndexedDocument is not correctly defined
     * @throws CommunicationException
     *    Raised if the IndexedDocument has not been sent to the services.
     * @throws ConversionException
     *    Raised if the IndexedDocument processing before sending to the
     *    services failed.
     */
    public function sendDocumentIndexDelete($documentId)
    {
        $this->container->get('communicator')->communicateIndexDelete($documentId);
    }
}
