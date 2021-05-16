<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

/**
 * Tests the API base class.
 *
 * @coversDefaultClass  \OpenEuropa\EuropaSearchClient\Api\ApiBase
 */
class ApiBaseTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::setConfiguration
     * @covers ::getConfigValue
     */
    public function testConfigResolver(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'http://example.com/search',
            // Config not defined in Search API schema.
            'foo' => 'bar',
        ]);
        $container = $client->getContainer();

        $class = new \ReflectionClass($container->get('search'));
        $method = $class->getMethod('getConfigValue');
        $method->setAccessible(true);

        $this->assertSame('foo', $method->invokeArgs($container->get('search'), ['apiKey']));
        $this->assertSame('http://example.com/search', $method->invokeArgs($container->get('search'), ['searchApiEndpoint']));
        $this->expectExceptionObject(new \InvalidArgumentException("Invalid config key: 'foo'. Valid keys: 'apiKey, searchApiEndpoint'."));
        $method->invokeArgs($container->get('search'), ['foo']);
    }

    /**
     * @covers ::setConfiguration
     */
    public function testMissingConfig(): void
    {
        $client = $this->getTestingClient();
        $class = new \ReflectionClass($client);
        $method = $class->getMethod('search');
        $method->setAccessible(true);
        $this->expectExceptionObject(new MissingOptionsException('The required options "apiKey", "searchApiEndpoint" are missing.'));
        $this->assertSame([], $method->invokeArgs($client, []));
    }

    /**
     * @covers ::setConfiguration
     */
    public function testInvalidEndpoint(): void
    {
        $client = $this->getTestingClient([
            'apiKey' => 'foo',
            'searchApiEndpoint' => 'INVALID_URL',
        ]);
        $this->expectExceptionObject(new InvalidOptionsException('The option "searchApiEndpoint" with value "INVALID_URL" is invalid.'));
        $client->getContainer()->get('search');
    }

    //    /**
    //     * Tests the addQueryParameters() method.
    //     *
    //     * @dataProvider addQueryParametersProvider
    //     *
    //     * @param string $uri
    //     *   The initial URI.
    //     * @param array $queryParameters
    //     *   A list of query parameters to append to the URI.
    //     * @param string $expected
    //     *   The expected resulting URI.
    //     */
    //    public function testAddQueryParameters(string $uri, array $queryParameters, string $expected): void
    //    {
    //        $api = $this->getApiBaseMock();
    //        $reflection = new \ReflectionClass($api);
    //        $method = $reflection->getMethod('addQueryParameters');
    //        $method->setAccessible(true);
    //
    //        $uri = $method->invokeArgs($api, [ $uri, $queryParameters]);
    //        $this->assertEquals($expected, $uri);
    //    }
    //
    //    /**
    //     * Data provider for the addQueryParameters() method.
    //     */
    //    public function addQueryParametersProvider(): array
    //    {
    //        return [
    //            'no query parameters' => [
    //                'http://www.example.org',
    //                [],
    //                'http://www.example.org',
    //            ],
    //            'one query parameter' => [
    //                'http://www.example.org',
    //                [
    //                    'a' => '1',
    //                ],
    //                'http://www.example.org?a=1',
    //            ],
    //            'two query parameters' => [
    //                'http://www.example.org',
    //                [
    //                    'a' => 1,
    //                    'b' => 'lorem',
    //                ],
    //                'http://www.example.org?a=1&b=lorem',
    //            ],
    //            'existing query parameters' => [
    //                'http://www.example.org?e=x',
    //                [
    //                    'a' => 1,
    //                    'b' => 'lorem',
    //                ],
    //                'http://www.example.org?e=x&a=1&b=lorem',
    //            ],
    //            'escaping query parameters' => [
    //                'http://www.example.org',
    //                [
    //                    'a' => 'lorem&ipsum',
    //                ],
    //                'http://www.example.org?a=lorem%26ipsum',
    //            ]
    //        ];
    //    }
    //
    //    /**
    //     * Tests the getMultipartStream() method.
    //     */
    //    public function testGetMultipartStream(): void
    //    {
    //        $api = $this->getApiBaseMock();
    //        $reflection = new \ReflectionClass($api);
    //        $method = $reflection->getMethod('getMultipartStream');
    //        $method->setAccessible(true);
    //
    //        $stream = $method->invokeArgs($api, [
    //            [
    //                'payload1' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida orci ut magna ornare interdum.',
    //                'payload2' => 'Pellentesque hendrerit nibh placerat, posuere ipsum nec, feugiat turpis.',
    //            ]
    //        ]);
    //
    //        $this->assertInstanceOf(MultipartStream::class, $stream);
    //        $boundary = $stream->getBoundary();
    //        $expected = "--$boundary\r\n"
    //        . "Content-Type: application/json\r\n"
    //        . "Content-Disposition: form-data; name=\"payload1\"; filename=\"blob\"\r\n"
    //        . "Content-Length: 99\r\n\r\n"
    //        . "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida orci ut magna ornare interdum.\r\n"
    //        . "--$boundary\r\n"
    //        . "Content-Type: application/json\r\n"
    //        . "Content-Disposition: form-data; name=\"payload2\"; filename=\"blob\"\r\n"
    //        . "Content-Length: 72\r\n\r\n"
    //        . "Pellentesque hendrerit nibh placerat, posuere ipsum nec, feugiat turpis.\r\n"
    //        . "--$boundary--\r\n";
    //        $this->assertEquals($expected, $stream->getContents());
    //    }
    //
    //    /**
    //     * Creates a mocked version of the ApiBase class.
    //     *
    //     * @return \PHPUnit\Framework\MockObject\MockObject
    //     *   The mocked class.
    //     */
    //    protected function getApiBaseMock(): MockObject
    //    {
    //        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
    //        $api = $this->getMockBuilder(ApiBase::class)
    //            ->setConstructorArgs([$client])
    //            ->getMockForAbstractClass();
    //        return $api;
    //    }
}
