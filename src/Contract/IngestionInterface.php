<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\IngestionResult;

interface IngestionInterface extends ApiInterface, TokenAwareInterface, LanguagesAwareInterface
{
    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\IngestionResult
     */
    public function ingest(): IngestionResult;

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
     * @param array|null $metadata
     * @return $this
     */
    public function setMetadata(?array $metadata): self;

    /**
     * @return array|null
     */
    public function getMetadata(): ?array;

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
