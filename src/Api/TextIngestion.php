<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\TextIngestionInterface;
use OpenEuropa\EuropaSearchClient\Model\IngestionResult;

/**
 * Ingestion API.
 */
class TextIngestion extends IngestionBase implements TextIngestionInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'textIngestionApiEndpoint' => $this->getEndpointSchema(),
        ] + parent::getConfigSchema();
    }

    /**
     * @inheritDoc
     */
    public function ingest(): IngestionResult
    {
        /** @var IngestionResult $ingestion */
        $ingestion = $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            IngestionResult::class,
            'json'
        );
        return $ingestion;
    }

    /**
     * @inheritDoc
     */
    public function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($text = $this->getText()) {
            $parts['text'] = $this->jsonEncoder->encode($text, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('textIngestionApiEndpoint');
    }

    /**
     * @inheritDoc
     */
    public function setText(?string $text): TextIngestionInterface
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getText(): ?string
    {
        return $this->text;
    }
}
