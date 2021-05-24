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
}
