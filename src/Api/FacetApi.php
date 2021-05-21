<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\FacetInterface;
use OpenEuropa\EuropaSearchClient\Model\Search;

/**
 * Facet API.
 */
class FacetApi extends SearchApiBase implements FacetInterface
{
    /**
     * @var string
     */
    protected $displayLanguage;

    /**
     * @inheritDoc
     */
    public function getFacets(): array
    {
        return $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            Search::class,
            'json'
        );
        return $search;
    }

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'facetApiEndpoint' => $this->getEndpointSchema(),
        ] + parent::getConfigSchema();
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('facetApiEndpoint');
    }

    /**
     * @inheritDoc
     */
    protected function getRequestMultipartStreamElements(): array
    {
        $parts = parent::getRequestMultipartStreamElements();

        if ($displayLanguage = $this->getDisplayLanguage()) {
            $parts['displayLanguage'] = $this->jsonEncoder->encode($displayLanguage, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setDisplayLanguage(?string $displayLanguage): FacetInterface
    {
        $this->displayLanguage = $displayLanguage;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDisplayLanguage(): ?string
    {
        return $this->displayLanguage;
    }
}
