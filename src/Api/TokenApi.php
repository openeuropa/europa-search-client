<?php

declare(strict_types = 1);

namespace OpenEuropa\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Model\Token;
use Symfony\Component\OptionsResolver\Options;

/**
 * Class representing the Token API endpoint.
 */
class TokenApi extends ApiBase
{

    /**
     * Token endpoint grant type
     *
     * @var string
     */
    private static $grantType = 'client_credentials';

    /**
     * Requests a access token.
     *
     * @param array $parameters
     *   The request parameters:
     *     - grant_type: The text to match in documents. Required.
     *     - consumerKey: The consumer key.
     *     - consumerSecret: The consumer secret.
     *
     * @return \OpenEuropa\EuropaSearchClient\Model\Token
     *   The token model.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     *   Thrown if an error happened while processing the request.
     */
    public function getAccessToken(array $parameters = []): Token
    {
        $resolver = $this->getOptionResolver();
        $parameters = $resolver->resolve($parameters);

        $queryParameters = ['grant_type' => $parameters['grant_type']];

        $base64_credentials = base64_encode($parameters['consumerKey'] . ':' . $parameters['consumerSecret']);
        $this->setRequestHeader('Authorization', "Basic $base64_credentials");

        $response = $this->send('POST', 'token', $queryParameters, [], true);

        /** @var Token $token */
        $token = $this->getSerializer()->deserialize((string)$response->getBody(), Token::class, 'json');

        return $token;
    }

    /**
     * @inheritDoc
     */
    protected function getOptionResolver(): Options
    {
        $resolver = parent::getOptionResolver();

        $resolver->setRequired('grant_type')
            ->setAllowedTypes('grant_type', 'string')
            ->setDefault('grant_type', self::$grantType);

        $resolver->setRequired('consumerKey')
            ->setAllowedTypes('consumerKey', 'string');

        $resolver->setRequired('consumerSecret')
            ->setAllowedTypes('consumerSecret', 'string');

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
