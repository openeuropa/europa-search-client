<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;


interface IngestionInterface extends ApiInterface
{
    /**
     * @param TokenInterface $tokenService
     *
     * @return $this
     */
    public function setTokenService(TokenInterface $tokenService): self;
}
