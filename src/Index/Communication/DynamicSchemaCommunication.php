<?php
/**
 * @file
 * Contains EC\EuropaSearch\Index\Communication\DynamicSchemaConverter.
 */

namespace EC\EuropaSearch\Index\Communication;

use EC\EuropaSearch\Index\Client\IndexedDocument;

/**
 * Class DynamicSchemaCommunication
 *
 * It implements CommunicationInterface.
 * It works with the Dynamic schema of the Europa Search services.
 *
 * @package EC\EuropaSearch\Index\Communication
 */
class DynamicSchemaCommunication implements CommunicationInterface
{

    private $serviceConfiguration;

    /**
     * DynamicSchemaConverter constructor.
     * @param ServiceConfiguration $serviceConfiguration
     */
    public function __construct(ServiceConfiguration $serviceConfiguration)
    {
        $this->serviceConfiguration = $serviceConfiguration;
    }

    /**
     * @inheritDoc
     *
     * @param IndexedDocument $indexedDocument
     *   The document to convert and communicate for transmission.
     */
    public function communicateContentIndexing(IndexedDocument $indexedDocument)
    {
        // TODO: Implement communicateContentIndexing() method.
    }

    /**
     * @inheritDoc
     *
     * @param string $documentId
     *   The document id of indexed document to delete.
     */
    public function communicateIndexDelete($documentId)
    {
        // TODO: Implement communicateIndexingDelete() method.
    }
}
