<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Endpoint\TokenEndpoint;

interface TokenAwareInterface
{
    /**
     * @param TokenEndpoint $tokenService
     *
     * @return $this
     */
    public function setTokenService(TokenEndpoint $tokenService): self;
}
