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
    protected function getConfigurationResolver(): OptionsResolver
    {
        $resolver = parent::getConfigurationResolver();

        $resolver->setRequired('consumerKey')
            ->setAllowedTypes('consumerKey', 'string');
        $resolver->setRequired('consumerSecret')
            ->setAllowedTypes('consumerSecret', 'string');

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function execute(): Token
    {
        /** @var Token $token */
        $token = $this->getSerializer()->deserialize(
            $this->send('POST')->getBody()->__toString(),
            Token::class,
            'json'
        );
        return $token;
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
