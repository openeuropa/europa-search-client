<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\OptionsResolver\Options;

/**
 * Tests the Client class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Client
 */
class ClientTest extends TestCase
{
    /**
     * Test class getters.
     *
     * @throws \ReflectionException
     */
    public function testGetters()
    {
        $client = $this->getClientMock();
        $reflection = new \ReflectionClass($client);
        $method = $reflection->getMethod('getHttpClient');
        $result = $method->invokeArgs($client, []);

        $this->assertTrue($result instanceof HttpClientInterface);

        $method = $reflection->getMethod('getRequestFactory');
        $result = $method->invokeArgs($client, []);

        $this->assertTrue($result instanceof RequestFactoryInterface);

        $method = $reflection->getMethod('getStreamFactory');
        $result = $method->invokeArgs($client, []);

        $this->assertTrue($result instanceof StreamFactoryInterface);

        $method = $reflection->getMethod('getConfiguration');
        $result = $method->invokeArgs($client, ['apiKey']);

        $this->assertEquals('apiKey', $result);

        $method = $reflection->getMethod('getOptionResolver');
        $method->setAccessible(true);
        $result = $method->invokeArgs($client, []);

        $this->assertTrue($result instanceof Options);
    }

    /**
     * Test the factory methods.
     *
     * @throws \ReflectionException
     */
    public function testFactoryMethods()
    {
        $client = $this->getClientMock();
        $reflection = new \ReflectionClass($client);

        $method = $reflection->getMethod('createRequest');
        $result = $method->invokeArgs($client, ['PUT', 'uri']);

        $this->assertTrue($result instanceof RequestInterface);

        $method = $reflection->getMethod('createStream');
        $result = $method->invokeArgs($client, ['stream content']);

        $this->assertTrue($result instanceof StreamInterface);

        $method = $reflection->getMethod('createStreamFromFile');
        $result = $method->invokeArgs($client, ['filename']);

        $this->assertTrue($result instanceof StreamInterface);

        $method = $reflection->getMethod('createStreamFromResource');
        $result = $method->invokeArgs($client, ['resource']);

        $this->assertTrue($result instanceof StreamInterface);
    }

    /**
     * Creates a mocked version of the ApiBase class.
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     *   The mocked class.
     */
    protected function getClientMock(): Client
    {
        $http_client = $this->getMockBuilder(HttpClientInterface::class)->getMock();
        $request_factory = $this->getMockBuilder(RequestFactoryInterface::class)->getMock();
        $stream_factory = $this->getMockBuilder(StreamFactoryInterface::class)->getMock();

        return new Client($http_client, $request_factory, $stream_factory, [
            'apiKey' => 'apiKey',
            'database' => 'database',
            'ingestion_api_endpoint' => 'ingestion_api_endpoint',
            'search_api_endpoint' => 'search_api_endpoint',
        ]);
    }
}
