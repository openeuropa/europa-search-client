<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Http\Factory\Guzzle\RequestFactory;
use OpenEuropa\EuropaSearchClient\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Base class for testing api classes.
 */
abstract class ApiTest extends TestCase
{
    /**
     * The serializer.
     *
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * Create http client with mock responses.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     * @return \Psr\Http\Client\ClientInterface
     */
    protected function getHttpClientMock(ResponseInterface $response): HttpClientInterface
    {
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);

        return new HttpClient(['handler' => $handlerStack]);
    }

    /**
     * Create a mocked Search client.
     *
     * @param \Psr\Http\Client\ClientInterface $http_client
     *   Http client with mock responses.
     * @return \OpenEuropa\EuropaSearchClient\ClientInterface
     *   Europa search client.
     */
    protected function getSearchClientMock(HttpClientInterface $http_client): ClientInterface
    {
        $client = $this->getMockBuilder(ClientInterface::class)
            ->getMock();
        $client->method('getConfiguration')
            ->will($this->returnValue('config'));
        $client->method('getHttpClient')
            ->will($this->returnValue($http_client));
        $client->method('getRequestFactory')
            ->willReturn(new RequestFactory());

        return $client;
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
