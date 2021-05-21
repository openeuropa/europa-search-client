<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Traits\ApiVersionAwareTrait;

/**
 * A class that represents an ingestion data transfer object.
 */
class IngestionResult extends ResultBase
{
    use ApiVersionAwareTrait;

    /**
     * The reference ID.
     *
     * @var string
     */
    protected $reference;

    /**
     * The tracking ID.
     *
     * @var string
     */
    protected $trackingId;

    /**
     * Returns the reference ID.
     *
     * @return string
     *   The reference ID.
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * Sets the reference ID.
     *
     * @param string $reference
     *   The reference ID.
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * Returns the tracking ID.
     *
     * @return string
     *   The tracking ID.
     */
    public function getTrackingId(): string
    {
        return $this->trackingId;
    }

    /**
     * Sets the tracking ID.
     *
     * @param string $trackingId
     *   The tracking ID.
     */
    public function setTrackingId(string $trackingId): void
    {
        $this->trackingId = $trackingId;
    }
}
