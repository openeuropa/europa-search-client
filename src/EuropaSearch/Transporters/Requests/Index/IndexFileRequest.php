<?php

namespace EC\EuropaSearch\Transporters\Requests\Index;

/**
 * Class IndexFileRequest.
 *
 * It covers the file indexing request.
 *
 * @package EC\EuropaSearch\Transporters\Requests\Index
 */
class IndexFileRequest extends AbstractIndexItemRequest
{

    /**
     * Gets the content of the indexed document.
     *
     * @return string
     *    The content of the indexed document.
     */
    public function getDocumentFile()
    {
        return $this->body['text']['file'];
    }

    /**
     * Sets the content of the indexed document.
     *
     * @param string $documentFile
     *    The document content to index.
     */
    public function setDocumentFile($documentFile)
    {
        $this->body['file'] = [
            'name' => 'file',
            // TODO: To check appropiate Guzzle structure.
            'contents' => '@.'.$documentFile,
            // 'contents' => fopen($documentFile, 'r'),
            //'headers' => ['content-type' => 'application/octet-stream'],
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
        return '/rest/ingestion';
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
