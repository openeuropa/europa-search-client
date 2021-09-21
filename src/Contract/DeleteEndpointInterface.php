<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface DeleteEndpointInterface extends EndpointInterface, TokenAwareInterface
{
    /**
     * @return bool
     */
    public function execute(): bool;

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
