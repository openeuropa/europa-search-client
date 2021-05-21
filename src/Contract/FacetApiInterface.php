<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Facet;

interface FacetApiInterface extends SearchApiBaseInterface
{
    /**
     * @return Facet
     */
    public function getFacets(): Facet;

    /**
     * @param string|null $displayLanguage
     * @todo Validate passed language code against ISO-639-1 in OEL-154.
     */
    public function setDisplayLanguage(?string $displayLanguage): self;

    /**
     * @return string|null
     */
    public function getDisplayLanguage(): ?string;
}
