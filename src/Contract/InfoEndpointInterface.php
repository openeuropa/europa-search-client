<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Contract;

use OpenEuropa\EuropaSearchClient\Model\Info;

interface InfoEndpointInterface extends EndpointInterface
{
    /**
     * @return Info
     */
    public function execute(): Info;
}
