<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\Metadata;

interface IngestionApiInterface extends ApiInterface, TokenAwareInterface, LanguagesAwareInterface
{
    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\Ingestion
     */
    public function ingest(): Ingestion;

    /**
     * @param string $uri
     * @return $this
     * @throws \InvalidArgumentException
     *   If $uri is not a valid URI.
     */
    public function setUri(string $uri): self;

    /**
     * @return string
     */
    public function getUri(): string;

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
     * @return string
     */
    public function getReference(): string;

    /**
     * @param array $aclUsers
     *
     * @return $this
     */
    public function setAclUsers(array $aclUsers): self;

    /**
     * @return array
     */
    public function getAclUsers(): array;

    /**
     * @param array $aclNoUsers
     *
     * @return $this
     */
    public function setAclNoUsers(array $aclNoUsers): self;

    /**
     * @return array
     */
    public function getAclNoUsers(): array;

    /**
     * @param array $aclGroups
     *
     * @return $this
     */
    public function setAclGroups(array $aclGroups): self;

    /**
     * @return array
     */
    public function getAclGroups(): array;

    /**
     * @param array $aclNoGroups
     *
     * @return $this
     */
    public function setAclNoGroups(array $aclNoGroups): self;

    /**
     * @return array
     */
    public function getAclNoGroups(): array;
}
