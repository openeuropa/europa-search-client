<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Model;

/**
 * A class that represents a collection of FacetValue.
 */
class FacetCollection implements \JsonSerializable
{
    /**
     * The list of facet.
     *
     * @var array
     */
    protected $collection = [];

    /**
     * Adds facet objects to the facet collection.
     *
     * @param string $apiVersion
     *   Usually a api version.
     * @param array  $facets
     *   The facets value.
     * @param string $terms
     *   Usually a term name.
     *
     * @return $this
     */
    public function addFacetdata(string $apiVersion, array $facets, string $terms): FacetCollection
    {
        $facet = new FacetValue();
        $facet->setApiVersion($apiVersion);
        $facet->setFacets($facets);
        $facet->setTerms($terms);
        $this->collection = $facet;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        $facet = $this->collection;

        return [
            'apiVersion' => $facet->getApiVersion(),
            'facets' => $facet->getFacets(),
            'terms' => $facet->getTerms(),
        ];
    }
}
