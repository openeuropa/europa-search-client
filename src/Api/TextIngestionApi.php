<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\TextIngestionApiInterface;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;

/**
 * Text ingestion API.
 */
class TextIngestionApi extends IngestionApiBase implements TextIngestionApiInterface
{
    /**
     * @var string|null
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
    public function ingest(): Ingestion
    {
        /** @var Ingestion $ingestion */
        $ingestion = $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            Ingestion::class,
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
    public function setText(?string $text): TextIngestionApiInterface
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
