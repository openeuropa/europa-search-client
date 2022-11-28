<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Exception\ParameterValueException;
use OpenEuropa\EuropaSearchClient\Model\Facets;
use Psr\Http\Message\UriInterface;

/**
 * Facet API endpoint.
 */
class FacetEndpoint extends SearchEndpointBase
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
     * @var array
     */
    protected $displayFields;

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
        $facets = $this->getSerializer()->deserialize(
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
        if ($displayFields = $this->getDisplayFields()) {
            $parts['displayFields']['content'] = $this->jsonEncoder->encode($displayFields, 'json');
        }

        return $parts;
    }

    /**
     * @inheritDoc
     */
    public function setFacetSort(?string $facetSort): self
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
    public function setDisplayLanguage(?string $displayLanguage): self
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

    /**
     * @inheritDoc
     */
    public function setDisplayFields(?array $displayFields): self
    {
        $this->displayFields = $displayFields;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDisplayFields(): ?array
    {
        return $this->displayFields;
    }
}
