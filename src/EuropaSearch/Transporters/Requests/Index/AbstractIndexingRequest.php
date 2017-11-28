<?php

namespace EC\EuropaSearch\Transporters\Requests\Index;

use EC\EuropaSearch\Transporters\Requests\AbstractRequest;

/**
 * Class AbstractIndexingRequest.
 *
 * It represent a generic indexing request content sent to the
 * Index Europa Search service.
 *
 * @package EC\EuropaSearch\Transporters\Requests\Index
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
}
