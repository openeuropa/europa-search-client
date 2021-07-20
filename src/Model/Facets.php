<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Traits\ApiVersionAwareTrait;

/**
 * A class that represents a facets data transfer object.
 */
class Facets
{
    use ApiVersionAwareTrait;

    /**
     * @var Facet[]
     * @todo This will be converted to a collection in OEL-166.
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-166
     */
    protected $facets;

    /**
     * @var string
     */
    protected $terms;

    /**
     * @param array $facets
     * @return $this
     * @todo The $facets parameter will be converted to a collection in OEL-166.
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-166
     */
    public function setFacets(array $facets): self
    {
        $this->facets = $facets;
        return $this;
    }

    /**
     * @return Facet[]
     * @todo This will be converted to a collection in OEL-166.
     * @see https://citnet.tech.ec.europa.eu/CITnet/jira/browse/OEL-166
     */
    public function getFacets(): array
    {
        return $this->facets;
    }

    /**
     * @param string $terms
     * @return $this
     */
    public function setTerms(string $terms): self
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * @return string
     */
    public function getTerms(): string
    {
        return $this->terms;
    }
}
