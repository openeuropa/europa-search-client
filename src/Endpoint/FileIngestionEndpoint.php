<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

/**
 * File ingestion API endpoint.
 */
class FileIngestionEndpoint extends IngestionEndpointBase
{
    /**
     * @var string|null
     */
    protected $file;

    /**
     * @inheritDoc
     */
    public function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($file = $this->getFile()) {
            $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
            $parts['file'] = [
                'content' => $file,
                'contentType' => $fileInfo->buffer($file),
            ];
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setFile(?string $file): self
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFile(): ?string
    {
        return $this->file;
    }
}
