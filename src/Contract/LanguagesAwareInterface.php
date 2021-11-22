<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface LanguagesAwareInterface
{
    /**
     * @param array|null $languages
     *
     * @return $this
     */
    public function setLanguages(?array $languages): self;

    /**
     * @return array|null
     */
    public function getLanguages(): ?array;
}
