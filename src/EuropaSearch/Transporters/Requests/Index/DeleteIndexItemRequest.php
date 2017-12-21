<?php

namespace EC\EuropaSearch\Transporters\Requests\Index;

/**
 * Class DeleteIndexItemRequest.
 *
 * It covers the indexing item deletion request.
 *
 * @package EC\EuropaSearch\Transporters\Requests\Index
 */
class DeleteIndexItemRequest extends AbstractIndexingRequest
{
    /**
     * {@inheritDoc}
     */
    public function getRequestMethod()
    {
        return 'DELETE';
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
