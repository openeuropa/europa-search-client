<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use OpenEuropa\EuropaSearchClient\Api\TokenApi;
use OpenEuropa\EuropaSearchClient\Model\Search;
use OpenEuropa\EuropaSearchClient\Model\Token;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Tests the TokenApi class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\TokenApi
 */
class TokenApiTest extends TestCase
{
    /**
     * The serializer.
     *
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * Tests the getAccessToken() method.
     *
     * @dataProvider provideTestTokenScenarios
     *
     * @param array $parameters
     *   The request parameters:
     * @param Search $expected
     *   The expected value.
     * @param array $response
     *   The http response body.
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testGetAccessToken(array $parameters, array $expected)
    {
        $http_client = $this->getHttpClientMock($expected);

        // TODO: Mock this client also.
        $client = new \OpenEuropa\EuropaSearchClient\Client($http_client, new RequestFactory(), new StreamFactory(), [
            'apiKey' => 'apiKey',
            'database' => 'database',
            'ingestion_api_endpoint' => 'ingestion_api_endpoint',
            'search_api_endpoint' => 'search_api_endpoint',
            'token_api_endpoint' => 'token_api_endpoint'
        ]);

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
        return [
            'simple text parameters' => [
                [
                    'consumerKey' => 'consumer_key_value',
                    'consumerSecret' => 'consumer_secret_value',
                    'grant_type' => 'client_credentials'
                ],
                [
                    'accessToken' => 'your jwt token',
                    'scope' => 'am_application_scope default',
                    'tokenType' => 'Bearer',
                    'expiresIn' => 3600,
                ]
            ],
        ];
    }

    /**
     * Create http client with mock responses.
     *
     * @param array $response
     *   The response body.
     * @return \Psr\Http\Client\ClientInterface
     */
    protected function getHttpClientMock(array $response): ClientInterface
    {
        $json = json_encode($response);
        $mock = new MockHandler([new Response(200, [], $json)]);
        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Returns a configured serializer to convert API responses.
     *
     * @return \Symfony\Component\Serializer\SerializerInterface
     *   The serializer.
     */
    protected function getSerializer(): SerializerInterface
    {
        if ($this->serializer === null) {
            $this->serializer = new Serializer([
                new GetSetMethodNormalizer(null, null, new PhpDocExtractor()),
                new ArrayDenormalizer(),
            ], [
                new JsonEncoder(),
            ]);
        }

        return $this->serializer;
    }
}
