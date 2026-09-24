<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Traits\ApiVersionAwareTrait;

/**
 * A class that represents an ingestion tracking transfer object.
 */
class IngestionTracking
{
    use ApiVersionAwareTrait;

    /**
     * @var string
     */
    protected $apiVersion;

    /**
     * @var string
     */
    protected $trackingId;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $message;

    /**
     * @inheritDoc
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * @inheritDoc
     */
    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTrackingId(): string
    {
        return $this->trackingId;
    }

    /**
     * @inheritDoc
     */
    public function setTrackingId(string $trackingId): self
    {
        $this->trackingId = $trackingId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
