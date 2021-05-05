<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Api\TokenApi;
use OpenEuropa\EuropaSearchClient\Model\Token;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the TokenApi class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\TokenApi
 */
class TokenApiTest extends ApiTest
{
    /**
     * Tests the getAccessToken() method.
     *
     * @dataProvider provideTestTokenScenarios
     *
     * @param array $parameters
     *   The request parameters:
     * @param array $expected
     *   The expected value.
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testGetAccessToken(array $parameters, array $expected, ResponseInterface $response)
    {
        $http_client = $this->getHttpClientMock($response);
        $client = $this->getSearchClientMock($http_client);
        $token_api = new TokenApi($client);

        $actual_object = $token_api->getAccessToken($parameters);
        $expected_object = $this->getSerializer()->denormalize($expected, Token::class);

        $this->assertEquals($expected_object, $actual_object);
    }

    /**
     * Data provider for the testGetAccessToken() method.
     */
    public function provideTestTokenScenarios(): array
    {
        $expected = [
            'accessToken' => 'your jwt token',
            'scope' => 'am_application_scope default',
            'tokenType' => 'Bearer',
            'expiresIn' => 3600,
        ];

        return [
            'token request' => [
                [
                    'consumerKey' => 'consumer_key_value',
                    'consumerSecret' => 'consumer_secret_value',
                    'grant_type' => 'client_credentials'
                ],
                $expected,
                new Response(200, [], json_encode($expected)),
            ],
        ];
    }
}
