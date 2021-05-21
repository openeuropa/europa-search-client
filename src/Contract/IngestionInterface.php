<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\IngestionResult;
use OpenEuropa\EuropaSearchClient\Model\Metadata;

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
     * @param \OpenEuropa\EuropaSearchClient\Model\Metadata $metadata
     * @return $this
     */
    public function setMetadata(Metadata $metadata): self;

    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\Metadata
     */
    public function getMetadata(): Metadata;

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
