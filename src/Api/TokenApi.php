<?php

declare(strict_types=1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Contract\TokenApiInterface;
use OpenEuropa\EuropaSearchClient\Model\Token;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Token API.
 */
class TokenApi extends ApiBase implements TokenApiInterface
{
    /**
     * @inheritDoc
     */
    public function execute(): Token
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
    protected function setConfiguration(array $configuration): void
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setRequired('consumerKey')
            ->setAllowedTypes('consumerKey', 'string')
            ->setDefault('consumerKey', $configuration['consumerKey']);
        $optionsResolver->setRequired('consumerSecret')
            ->setAllowedTypes('consumerSecret', 'string')
            ->setDefault('consumerSecret', $configuration['consumerSecret']);
        $optionsResolver->setRequired('tokenApiEndpoint')
            ->setAllowedTypes('tokenApiEndpoint', 'string')
            ->setDefault('tokenApiEndpoint', $configuration['tokenApiEndpoint'])
            ->setAllowedValues('tokenApiEndpoint', function (string $value) {
                return filter_var($value, FILTER_VALIDATE_URL);
            });
        $this->configuration = $optionsResolver->resolve($configuration);
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
        ] + parent::getRequestHeaders();
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
