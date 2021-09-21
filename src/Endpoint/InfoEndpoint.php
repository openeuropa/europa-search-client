<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Contract\InfoEndpointInterface;
use OpenEuropa\EuropaSearchClient\Model\Info;

class InfoEndpoint extends EndpointBase implements InfoEndpointInterface
{
    /**
     * @inheritDoc
     */
    public function execute(): Info
    {
        /** @var Info $info */
        $info = $this->getSerializer()->deserialize(
            $this->send('GET')->getBody()->__toString(),
            Info::class,
            'json'
        );
        return $info;
    }
}
