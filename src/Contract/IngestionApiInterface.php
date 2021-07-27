<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\Metadata;
use Psr\Http\Message\UriInterface;

interface IngestionApiInterface extends TokenAwareInterface, LanguagesAwareInterface
{
    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\Ingestion
     */
    public function execute(): Ingestion;

    /**
     * @param UriInterface $uri
     * @return $this
     */
    public function setUri(UriInterface $uri): self;

    /**
     * @return UriInterface
     */
    public function getUri(): UriInterface;

    /**
     * @param Metadata|null $metadata
     * @return $this
     */
    public function setMetadata(?Metadata $metadata): self;

    /**
     * @return Metadata|null
     */
    public function getMetadata(): ?Metadata;

    /**
     * @param string|null $reference
     * @return $this
     */
    public function setReference(?string $reference): self;

    /**
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * @param array|null $aclUsers
     * @return $this
     */
    public function setAclUsers(?array $aclUsers): self;

    /**
     * @return array|null
     */
    public function getAclUsers(): ?array;

    /**
     * @param array|null $aclNoUsers
     * @return $this
     */
    public function setAclNoUsers(?array $aclNoUsers): self;

    /**
     * @return array|null
     */
    public function getAclNoUsers(): ?array;

    /**
     * @param array|null $aclGroups
     * @return $this
     */
    public function setAclGroups(?array $aclGroups): self;

    /**
     * @return array|null
     */
    public function getAclGroups(): ?array;

    /**
     * @param array|null $aclNoGroups
     * @return $this
     */
    public function setAclNoGroups(?array $aclNoGroups): self;

    /**
     * @return array|null
     */
    public function getAclNoGroups(): ?array;
}
