<?php

namespace EC\EuropaSearch\Transporters\Requests\Index;

use EC\EuropaSearch\Transporters\Requests\AbstractRequest;

/**
 * Class IndexedItemDeletionRequest.
 *
 * It covers the indexing item deletion request.
 *
 * @package EC\EuropaSearch\Transporters\Requests\Index
 */
class IndexedItemDeletionRequest extends AbstractRequest
{

    /**
     * Gets the id of the document to delete in the index.
     *
     * @return string
     *   The id to delete.
     */
    public function getDocumentId()
    {
        return $this->query['reference'];
    }

    /**
     * Sets the id of the document to delete in the index.
     *
     * @param string $documentId
     *   The id to delete.
     */
    public function setDocumentId($documentId)
    {
        $this->query['reference'] = $documentId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestMethod()
    {
        return 'Delete';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestURI()
    {
        return '/rest/ingestion';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestOptions()
    {
        return [
            'query' => $this->getRequestQuery(),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function addConvertedComponents(array $components)
    {
        return;
    }
}
