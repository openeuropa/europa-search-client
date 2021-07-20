<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface DeleteApiInterface extends ApiInterface, TokenAwareInterface
{
    /**
     * @return bool
     */
    public function deleteDocument(): bool;

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
