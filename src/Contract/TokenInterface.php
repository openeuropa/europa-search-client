<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\TokenResult;

interface TokenInterface extends ApiInterface
{
    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\TokenResult
     */
    public function getToken(): TokenResult;
}
