<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\IngestionInterface;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;
use Psr\Http\Message\UriInterface;

/**
 * Ingestion API.
 */
abstract class IngestionBase extends ApiBase implements IngestionInterface
{
    use TokenAwareTrait;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string[]
     */
    protected $languages;

    /**
     * @var array
     * @todo Metadata is a complex structure and it requires its own type.
     */
    protected $metadata;

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'database' => [
                'type' => 'string',
                'required' => true,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRequestUriQuery(UriInterface $uri): array
    {
        return [
            'apiKey' => $this->getConfigValue('apiKey'),
            'database' => $this->getConfigValue('database'),
            'uri' => $this->getUri(),
            'reference' => $this->getReference(),
        ] + parent::getRequestUriQuery($uri);
    }

    /**
     * @inheritDoc
     */
    public function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($languages = $this->getLanguages()) {
            $parts['languages'] = $this->jsonEncoder->encode($languages, 'json');
        }
        if ($metadata = $this->getMetadata()) {
            $parts['metadata'] = $this->jsonEncoder->encode($metadata, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setUri(string $uri): IngestionInterface
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function setLanguages(?array $languages): IngestionInterface
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @inheritDoc
     */
    public function setMetadata(?array $metadata): IngestionInterface
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * @inheritDoc
     */
    public function setReference(?string $reference): IngestionInterface
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getReference(): string
    {
        return $this->reference;
    }
}
