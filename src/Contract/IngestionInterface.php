<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Contract\TokenInterface;

interface IngestionInterface extends ApiInterface
{
    /**
     * @param TokenInterface $token
     *
     * @return $this
     */
    public function setToken(TokenInterface $token): self;
}
