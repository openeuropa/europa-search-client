<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface;
use OpenEuropa\EuropaSearchClient\Contract\SearchApiBaseInterface;
use OpenEuropa\EuropaSearchClient\Exception\EuropaSearchApiInvalidParameterValueException;
use OpenEuropa\EuropaSearchClient\Model\Facets;
use Psr\Http\Message\UriInterface;

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
     * @var string[]
     */
    protected const ALLOWED_SORT_VALUES = [
        'DATE',
        'REVERSE_DATE',
        'ALPHABETICAL',
        'REVERSE_ALPHABETICAL',
        'DOCUMENT_COUNT',
        'REVERSE_DOCUMENT_COUNT',
        'NUMBER_DECREASING',
        'NUMBER_INCREASING',
    ];

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
    protected function getRequestUriQuery(UriInterface $uri): array
    {
        $query = parent::getRequestUriQuery($uri);

        if ($sort = $this->getSort()) {
            $query['sort'] = $sort;
        }

        return $query;
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
    public function setSort($sort): SearchApiBaseInterface
    {
        if (!in_array($sort, self::ALLOWED_SORT_VALUES)) {
            $allowed_values = implode(', ', self::ALLOWED_SORT_VALUES);
            throw new EuropaSearchApiInvalidParameterValueException("`setSort()` received invalid argument `$sort`, must be one of $allowed_values.");
        }
        $this->sort = $sort;
        return $this;
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
