<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\FacetValue;

interface FacetInterface extends SearchApiBaseInterface
{
    /**
     * @return FacetValue[]
     */
    public function getFacets(): array;

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
