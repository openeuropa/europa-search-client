<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface DeleteInterface extends ApiInterface, TokenAwareInterface
{
    /**
     * @return bool
     */
    public function delete(): bool;

    /**
     * @param string $reference
     * @return $this
     */
    public function setReference(string $reference): self;

    /**
     * @return string
     */
    public function getReference(): string;
}
