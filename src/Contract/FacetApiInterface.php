<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Facets;

interface FacetApiInterface extends SearchApiBaseInterface
{
    /**
     * @return Facets
     */
    public function execute(): Facets;

    /**
     * @param string|null $displayLanguage
     * @todo Validate passed language code against ISO-639-1 in OEL-154.
     *
     * @return $this
     */
    public function setDisplayLanguage(?string $displayLanguage): self;

    /**
     * @return string|null
     */
    public function getDisplayLanguage(): ?string;

    /**
     * @param string|null $facetSort
     *
     * @return $this
     */
    public function setFacetSort(?string $facetSort): self;

    /**
     * @return string
     */
    public function getFacetSort(): string;
}
