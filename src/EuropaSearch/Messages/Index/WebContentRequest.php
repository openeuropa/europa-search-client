<?php
/**
 * @file
 * Contains EC\EuropaSearch\Messages\Index\WebContentRequest.
 */

namespace EC\EuropaSearch\Messages\Index;

/**
 * Class WebContentRequest.
 *
 *  It covers the web content indexing request.
 *
 * @package EC\EuropaSearch\Messages\Index
 */
class WebContentRequest extends AbstractIndexingRequest
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
        return '/es/ingestion-api/rest/ingestion/text';
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
