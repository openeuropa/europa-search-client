<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface TokenAwareInterface
{
    /**
     * @param TokenInterface $tokenService
     *
     * @return $this
     */
    public function setTokenService(TokenInterface $tokenService): self;
}
