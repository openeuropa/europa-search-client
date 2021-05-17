<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\IngestionResult;

interface IngestionInterface extends ApiInterface, TokenAwareInterface
{
    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\IngestionResult
     */
    public function ingest(): IngestionResult;

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri(string $uri): self;

    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @param array|null $languages
     * @return $this
     */
    public function setLanguages(?array $languages): self;

    /**
     * @return string[]
     */
    public function getLanguages(): array;

    /**
     * @param array|null $metadata
     * @return $this
     * @todo Metadata is a complex structure and it requires its own type.
     */
    public function setMetadata(?array $metadata): self;

    /**
     * @return array|null
     * @todo Metadata is a complex structure and it requires its own type.
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
