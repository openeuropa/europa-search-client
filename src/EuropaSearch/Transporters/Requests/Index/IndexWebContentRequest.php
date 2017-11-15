<?php

namespace EC\EuropaSearch\Transporters\Requests\Index;

/**
 * Class IndexWebContentRequest.
 *
 * It covers the web content indexing request.
 *
 * @package EC\EuropaSearch\Transporters\Requests\Index
 */
class IndexWebContentRequest extends AbstractIndexItemRequest
{

    /**
     * Gets the content of the indexed document.
     *
     * @return string
     *    The content of the indexed document.
     */
    public function getDocumentContent()
    {
        return $this->body['text']['contents'];
    }

    /**
     * Sets the content of the indexed document.
     *
     * @param string $documentContent
     *    The document content to index.
     */
    public function setDocumentContent($documentContent)
    {
        $this->body['text'] = [
            'name' => 'text',
            'contents' => $documentContent,
            'headers' => ['content-type' => 'application/json'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestURI()
    {
        return '/rest/ingestion/text';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestOptions()
    {
        return [
            'multipart' => $this->getRequestBody(),
            'query' => $this->getRequestQuery(),
        ];
    }
}
