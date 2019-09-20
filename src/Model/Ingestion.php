<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents an ingestion data transfer object.
 */
class Ingestion
{

    /**
     * The API version.
     *
     * @var string
     */
    protected $apiVersion;

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
     * Returns the API version.
     *
     * @return string
     *   The API version.
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * Sets the API version.
     *
     * @param string $apiVersion
     *   The API version.
     */
    public function setApiVersion(string $apiVersion): void
    {
        $this->apiVersion = $apiVersion;
    }

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
