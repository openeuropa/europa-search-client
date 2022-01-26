<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Traits;

trait ApiVersionAwareTrait
{
    /**
     * The API version.
     *
     * @var string|null
     */
    protected $apiVersion;

    /**
     * @param string|null $apiVersion
     * @return $this
     */
    public function setApiVersion(?string $apiVersion): self
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiVersion(): ?string
    {
        return $this->apiVersion;
    }
}
