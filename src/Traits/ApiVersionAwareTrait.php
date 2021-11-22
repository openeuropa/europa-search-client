<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Traits;

trait ApiVersionAwareTrait
{
    /**
     * The API version.
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * @param string $apiVersion
     * @return $this
     */
    public function setApiVersion(string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }
}
