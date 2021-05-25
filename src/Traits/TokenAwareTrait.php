<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Traits;

use OpenEuropa\EuropaSearchClient\Contract\TokenAwareInterface;
use OpenEuropa\EuropaSearchClient\Contract\TokenApiInterface;

trait TokenAwareTrait
{
    /**
     * @var TokenApiInterface
     */
    protected $tokenService;

    /**
     * @inheritDoc
     */
    public function setTokenService(TokenApiInterface $tokenService): TokenAwareInterface
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
        ] + parent::getRequestHeaders();
    }
}
