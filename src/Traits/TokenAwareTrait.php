<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Traits;

use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Endpoint\TokenEndpoint;

trait TokenAwareTrait
{
    /**
     * @var TokenEndpoint
     */
    protected $tokenService;

    /**
     * @inheritDoc
     */
    public function setTokenService(TokenEndpoint $tokenService): TokenAwareInterface
    {
        $this->tokenService = $tokenService;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRequestHeaders(): array
    {
        $token = $this->tokenService->execute();
        return [
            'Authorization' => "{$token->getTokenType()} {$token->getAccessToken()}",
            'Authorization-propagation' => $token->getAccessToken(),
        ] + parent::getRequestHeaders();
    }
}
