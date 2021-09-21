<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Token;

interface TokenEndpointInterface extends EndpointInterface
{
    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\Token
     */
    public function execute(): Token;
}
