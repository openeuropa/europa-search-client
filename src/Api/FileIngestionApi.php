<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\FileIngestionApiInterface;

/**
 * File ingestion API.
 */
class FileIngestionApi extends IngestionApiBase implements FileIngestionApiInterface
{
    /**
     * @var string|null
     */
    protected $file;

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'fileIngestionApiEndpoint' => $this->getEndpointSchema(),
        ] + parent::getConfigSchema();
    }

    /**
     * @inheritDoc
     */
    public function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($file = $this->getFile()) {
            $parts['file'] = $this->jsonEncoder->encode($file, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('fileIngestionApiEndpoint');
    }

    /**
     * @inheritDoc
     */
    public function setFile(?string $file): FileIngestionApiInterface
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
