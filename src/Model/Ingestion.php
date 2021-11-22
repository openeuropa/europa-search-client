<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Traits\ApiVersionAwareTrait;

/**
 * A class that represents an ingestion data transfer object.
 */
class Ingestion
{
    use ApiVersionAwareTrait;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $trackingId;

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $trackingId
     * @return $this
     */
    public function setTrackingId(string $trackingId): self
    {
        $this->trackingId = $trackingId;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingId(): string
    {
        return $this->trackingId;
    }
}
