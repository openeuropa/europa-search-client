<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

interface TokenAwareInterface
{
    /**
     * @param TokenApiInterface $tokenService
     *
     * @return $this
     */
    public function setTokenService(TokenApiInterface $tokenService): self;
}
