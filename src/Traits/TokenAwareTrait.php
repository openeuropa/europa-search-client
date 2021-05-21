<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Traits;

use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenInterface;

trait TokenAwareTrait
{
    /**
     * @var TokenInterface
     */
    protected $tokenService;

    /**
     * @inheritDoc
     */
    public function setTokenService(TokenInterface $tokenService): TokenAwareInterface
    {
        $this->tokenService = $tokenService;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRequestHeaders(): array
    {
        $token = $this->tokenService->getToken();
        return [
            'Authorization' => "{$token->getTokenType()} {$token->getAccessToken()}",
            'Authorization-propagation' => $token->getAccessToken(),
        ];
    }
}
