<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\TokenApiInterface;
use OpenEuropa\EuropaSearchClient\Model\Token;

/**
 * Token API.
 */
class TokenApi extends ApiBase implements TokenApiInterface
{
    /**
     * @inheritDoc
     */
    public function getToken(): Token
    {
        /** @var Token $token */
        $token = $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            Token::class,
            'json'
        );
        return $token;
    }

    /**
     * @inheritDoc
     */
    public function getConfigSchema(): array
    {
        return [
            'tokenApiEndpoint' => $this->getEndpointSchema(),
            'consumerKey' => $this->getRequiredStringSchema(),
            'consumerSecret' => $this->getRequiredStringSchema(),
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getEndpointUri(): string
    {
        return $this->getConfigValue('tokenApiEndpoint');
    }

    /**
     * @return array
     */
    protected function getRequestHeaders(): array
    {
        return [
            'Authorization' => "Basic {$this->getAuthorizationHash()}",
        ];
    }

    /**
     * @return array
     */
    protected function getRequestFormElements(): array
    {
        return [
            'grant_type' => 'client_credentials',
        ];
    }

    /**
     * @return string
     */
    protected function getAuthorizationHash(): string
    {
        $consumerKey = $this->getConfigValue('consumerKey');
        $consumerSecret = $this->getConfigValue('consumerSecret');
        return base64_encode("{$consumerKey}:{$consumerSecret}");
    }
}
