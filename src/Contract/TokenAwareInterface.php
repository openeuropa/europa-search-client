<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface TokenAwareInterface
{
    /**
     * @param TokenEndpointInterface $tokenService
     *
     * @return $this
     */
    public function setTokenService(TokenEndpointInterface $tokenService): self;
}
