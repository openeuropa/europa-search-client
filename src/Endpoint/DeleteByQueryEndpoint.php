<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Endpoint;

use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Traits\TokenAwareTrait;

/**
 * Delete by Query API endpoint.
 */
class DeleteByQueryEndpoint extends SearchEndpointBase implements TokenAwareInterface
{
    use TokenAwareTrait;

    /**
     * @inheritDoc
     */
    public function execute(): bool
    {
        $response = $this->send('DELETE');
        return $response->getStatusCode() === 200;
    }
}
