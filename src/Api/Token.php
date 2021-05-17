<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\TokenInterface;
use OpenEuropa\EuropaSearchClient\Model\TokenResult;

/**
 * Token API.
 */
class Token extends ApiBase implements TokenInterface
{
    /**
     * @inheritDoc
     */
    public function getToken(): TokenResult
    {
        /** @var TokenResult $token */
        $token = $this->serializer->deserialize(
            $this->send('POST')->getBody()->__toString(),
            TokenResult::class,
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
            'tokenApiEndpoint' => [
                'type' => 'string',
                'required' => true,
                'value' => function (string $value) {
                    return filter_var($value, FILTER_VALIDATE_URL);
                },
            ],
            'consumerKey' => [
                'type' => 'string',
                'required' => true,
            ],
            'consumerSecret' => [
                'type' => 'string',
                'required' => true,
            ],
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
