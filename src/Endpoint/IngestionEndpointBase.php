<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Contract\LanguagesAwareInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\Metadata;
use OpenEuropa\EuropaSearchClient\Traits\LanguagesAwareTrait;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;
use Psr\Http\Message\UriInterface;

/**
 * Ingestion API endpoint.
 */
abstract class IngestionEndpointBase extends DatabaseEndpointBase implements LanguagesAwareInterface, TokenAwareInterface
{
    use LanguagesAwareTrait;
    use TokenAwareTrait;

    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var string|null
     */
    protected $reference;

    /**
     * @var Metadata|null
     */
    protected $metadata;

    /**
     * @var string[]|null
     */
    protected $aclUsers;

    /**
     * @var string[]|null
     */
    protected $aclNoUsers;

    /**
     * @var string[]|null
     */
    protected $aclGroups;

    /**
     * @var string[]|null
     */
    protected $aclNoGroups;

    /**
     * @inheritDoc
     */
    public function execute(): Ingestion
    {
        /** @var Ingestion $ingestion */
        $ingestion = $this->getSerializer()->deserialize(
            $this->send('POST')->getBody()->__toString(),
            Ingestion::class,
            'json'
        );
        return $ingestion;
    }

    /**
     * @inheritDoc
     */
    public function getRequestUriQuery(UriInterface $uri): array
    {
        $query = [
            'apiKey' => $this->getConfigValue('apiKey'),
            'database' => $this->getConfigValue('database'),
            'uri' => $this->getUri()->__toString(),
        ] + parent::getRequestUriQuery($uri);

        if ($languages = $this->getLanguages()) {
            $query['language'] = $this->jsonEncoder->encode($languages, 'json');
        }
        if ($reference = $this->getReference()) {
            $query['reference'] = $reference;
        }

        return $query;
    }

    /**
     * @inheritDoc
     */
    public function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($this->getMetadata()->count()) {
            $parts['metadata']['content'] = $this->jsonEncoder->encode($this->getMetadata(), 'json');
        }
        if ($aclUsers = $this->getAclUsers()) {
            $parts['aclUsers']['content'] = $this->jsonEncoder->encode($aclUsers, 'json');
        }
        if ($aclNolUsers = $this->getAclNoUsers()) {
            $parts['aclNolUsers']['content'] = $this->jsonEncoder->encode($aclNolUsers, 'json');
        }
        if ($aclGroups = $this->getAclGroups()) {
            $parts['aclGroups']['content'] = $this->jsonEncoder->encode($aclGroups, 'json');
        }
        if ($aclNoGroups = $this->getAclNoGroups()) {
            $parts['aclNoGroups']['content'] = $this->jsonEncoder->encode($aclNoGroups, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setUri(UriInterface $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function setMetadata(?Metadata $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(): ?Metadata
    {
        return $this->metadata;
    }

    /**
     * @inheritDoc
     */
    public function setReference(?string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @inheritDoc
     */
    public function setAclUsers(?array $aclUsers): self
    {
        $this->aclUsers = $aclUsers;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAclUsers(): ?array
    {
        return $this->aclUsers;
    }

    /**
     * @inheritDoc
     */
    public function setAclNoUsers(?array $aclNoUsers): self
    {
        $this->aclNoUsers = $aclNoUsers;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAclNoUsers(): ?array
    {
        return $this->aclNoUsers;
    }

    /**
     * @inheritDoc
     */
    public function setAclGroups(?array $aclGroups): self
    {
        $this->aclGroups = $aclGroups;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAclGroups(): ?array
    {
        return $this->aclGroups;
    }

    /**
     * @inheritDoc
     */
    public function setAclNoGroups(?array $aclNoGroups): self
    {
        $this->aclNoGroups = $aclNoGroups;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAclNoGroups(): ?array
    {
        return $this->aclNoGroups;
    }
}
