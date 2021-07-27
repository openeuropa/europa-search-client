<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Token;

interface TokenApiInterface extends ApiInterface
{
    /**
     * @return \OpenEuropa\EuropaSearchClient\Model\Token
     */
    public function execute(): Token;
}
