<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a facetvalue data object.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class FacetValue
{

    /**
     * The API version.
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * An array of Facets.
     *
     * @var \OpenEuropa\EuropaSearchClient\Model\Facet[]
     */
    protected $facets;

    /**
     * The search terms.
     *
     * @var string
     */
    protected $terms;

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
     */
    public function setApiVersion(string $apiVersion): void
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * Returns the list of result facets.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Facet[]
     *   An array of facets.
     */
    public function getFacets(): array
    {
        return $this->facets;
    }

    /**
     * Sets the list of result facets.
     *
     * @param \OpenEuropa\EuropaSearchClient\Model\Facet[] $results
     *   An array of facets.
     */
    public function setFacets(array $results): void
    {
        $this->facets = $results;
    }

    /**
     * Returns the facet terms.
     *
     * @return string
     *   The facet terms.
     */
    public function getTerms(): string
    {
        return $this->terms;
    }

    /**
     * Sets the facet terms.
     *
     * @param string $terms
     *   The facet terms.
     */
    public function setTerms(string $terms): void
    {
        $this->terms = $terms;
    }
}
