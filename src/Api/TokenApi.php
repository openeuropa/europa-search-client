<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Model\Search;
use OpenEuropa\EuropaSearchClient\Model\Token;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class representing the Token API endpoint.
 */
class TokenApi extends ApiBase
{

    /**
     * @var string Token endpoint grant type
     */
    private static $grantType = 'client_credentials';

    /**
     * Executes a search.
     *
     * @param array $parameters
     *   The request parameters:
     *     - text: The text to match in documents. Required.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Search
     *   The search model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function getAccessToken(array $parameters = []): Token
    {
        $resolver = $this->getOptionResolver();
        $parameters = $resolver->resolve($parameters);
        $queryParameters = ["grant_type" => $parameters['grant_type']];
        $authorizationCredentials = $parameters['consumerKey'] . ':' . $parameters['consumerSecret'];

        $response = $this->send('POST', 'token', $queryParameters, [], true, $authorizationCredentials);

        /** @var Search $token */
        $token = $this->getSerializer()->deserialize((string)$response->getBody(), Token::class, 'json');

        return $token;
    }

    /**
     * @inheritDoc
     */
    protected function getOptionResolver(): OptionsResolver
    {
        $resolver = parent::getOptionResolver();

        $resolver->setRequired('grant_type')
            ->setAllowedTypes('grant_type', 'string')
            ->setDefault('grant_type', self::$grantType);

        $resolver->setRequired('consumerKey')
            ->setAllowedTypes('consumerKey', 'string')
            ->setDefault('consumerKey', self::$grantType);

        $resolver->setRequired('consumerSecret')
            ->setAllowedTypes('consumerSecret', 'string')
            ->setDefault('consumerSecret', self::$grantType);

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    protected function prepareUri(string $path, array $queryParameters = []): string
    {
        $base_path = $this->client->getConfiguration('token_api_endpoint');
        $uri = rtrim($base_path, '/') . '/' . ltrim($path, '/');

        return $this->addQueryParameters($uri, $queryParameters);
    }
}
