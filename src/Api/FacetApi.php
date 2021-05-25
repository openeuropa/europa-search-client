<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface;
use OpenEuropa\EuropaSearchClient\Model\Facets;

/**
 * Facet API.
 */
class FacetApi extends SearchApiBase implements FacetApiInterface
{
    /**
     * @var string
     */
    protected $displayLanguage;

    /**
     * @inheritDoc
     */
    public function getFacets(): Facets
    {
        /** @var Facets $facets */
        $facets = $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            Facets::class,
            'json'
        );
        return $facets;
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
            $parts['displayLanguage']['content'] = $this->jsonEncoder->encode($displayLanguage, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setDisplayLanguage(?string $displayLanguage): FacetApiInterface
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
