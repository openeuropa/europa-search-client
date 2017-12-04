<?php

namespace EC\EuropaSearch\Transporters\Requests\Index;

use GuzzleHttp\Psr7;

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
     * @return resource
     *    The content of the indexed document.
     */
    public function getDocumentFile()
    {
        return $this->body['file']['contents'];
    }

    /**
     * Sets the content of the indexed document.
     *
     * @param string $documentFile
     *    The document content to index.
     */
    public function setDocumentFile($documentFile)
    {
        if (empty($documentFile)) {
            $this->body['file'] = null;

            return;
        }

        $mimeType = mime_content_type($documentFile);
        $stream = Psr7\stream_for(fopen($documentFile, 'r'));

        $this->body['file'] = [
            'name' => 'file',
            'contents' => $stream,
            'headers' => ['content-type' => $mimeType],
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
