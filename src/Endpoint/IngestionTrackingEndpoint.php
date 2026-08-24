<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Model\IngestionTracking;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;

/**
 * Ingestion tracking API endpoint.
 */
class IngestionTrackingEndpoint extends EndpointBase implements TokenAwareInterface
{
    use TokenAwareTrait;

    /**
     * @var array
     */
    protected $trackingIds = [];

    public function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($tracking_ids = $this->getTrackingIds()) {
            $parts['trackingIds'] = [
                'content' => json_encode($tracking_ids),
                'type' => 'text/json',
            ];
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function execute(): array
    {
        /** @var IngestionTracking[] $ingestion */
        $ingestion = $this->getSerializer()->deserialize(
            $this->send('POST')->getBody()->__toString(),
            'OpenEuropa\EuropaSearchClient\Model\IngestionTracking[]',
            'json'
        );
        return $ingestion;
    }

    /**
     * Get the tracking ids.
     *
     * @return array
     *   The tracking ids.
     */
    public function getTrackingIds(): array
    {
        return $this->trackingIds;
    }

    /**
     * Set the tracking ids.
     *
     * @param array $trackingIds
     *  The tracking ids.
     *
     * @return $this
     *   The endpoint.
     */
    public function setTrackingIds(array $trackingIds): self
    {
        $this->trackingIds = $trackingIds;
        return $this;
    }
}
