<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents an ingestion data transfer object.
 */
class IngestionResult
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
     *
     * @return $this
     */
    public function setApiVersion(string $apiVersion): Ingestion
    {
        $this->apiVersion = $apiVersion;

        return $this;
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
     *
     * @return $this
     */
    public function setReference(string $reference): Ingestion
    {
        $this->reference = $reference;

        return $this;
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
     *
     * @return $this
     */
    public function setTrackingId(string $trackingId): Ingestion
    {
        $this->trackingId = $trackingId;

        return $this;
    }
}
