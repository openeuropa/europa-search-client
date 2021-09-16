<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\FacetApiInterface;
use OpenEuropa\EuropaSearchClient\Exception\ParameterValueException;
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
    protected $facetSort;

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
    public function execute(): Facets
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
    protected function getRequestUriQuery(UriInterface $uri): array
    {
        $query = parent::getRequestUriQuery($uri);

        if ($facetSort = $this->getFacetSort()) {
            $query['sort'] = $facetSort;
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
    public function setFacetSort(?string $facetSort): FacetApiInterface
    {
        if ($facetSort !== null && !in_array($facetSort, static::ALLOWED_SORT_VALUES, true)) {
            $allowedValues = implode("', '", static::ALLOWED_SORT_VALUES);
            throw new ParameterValueException("::setFacetSort() received invalid argument '{$facetSort}', must be one of '{$allowedValues}'.");
        }
        $this->facetSort = $facetSort;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFacetSort(): string
    {
        return $this->facetSort ?: 'DOCUMENT_COUNT';
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
