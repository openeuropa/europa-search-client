<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\IngestionInterface;
use OpenEuropa\EuropaSearchClient\Traits\LanguagesAwareTrait;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;
use Psr\Http\Message\UriInterface;

/**
 * Ingestion API.
 */
abstract class IngestionBase extends ApiBase implements IngestionInterface
{
    use LanguagesAwareTrait;
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
            'apiKey' => [
                'type' => 'string',
                'required' => true,
            ],
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
        if (!filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Passed '{$uri}' string is not a valid URI.");
        }
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
