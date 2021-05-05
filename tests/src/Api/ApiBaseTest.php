<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\MultipartStream;
use OpenEuropa\EuropaSearchClient\Api\ApiBase;
use OpenEuropa\EuropaSearchClient\ClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Tests the API base class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\ApiBase
 */
class ApiBaseTest extends TestCase
{

    /**
     * Tests the setRequestHeader() method.
     */
    public function testSetRequestHeader(): void
    {
        $api = $this->getApiBaseMock();
        $reflection = new \ReflectionClass($api);
        $method = $reflection->getMethod('setRequestHeader');
        $method->setAccessible(true);
        $property = $reflection->getProperty('request_headers');
        $property->setAccessible(true);

        $method->invokeArgs($api, ['header_name', 'value']);

        $this->assertEquals(['header_name' => 'value'], $property->getValue($api));
    }

    /**
     * Tests the addQueryParameters() method.
     *
     * @dataProvider addQueryParametersProvider
     *
     * @param string $uri
     *   The initial URI.
     * @param array $queryParameters
     *   A list of query parameters to append to the URI.
     * @param string $expected
     *   The expected resulting URI.
     */
    public function testAddQueryParameters(string $uri, array $queryParameters, string $expected): void
    {
        $api = $this->getApiBaseMock();
        $reflection = new \ReflectionClass($api);
        $method = $reflection->getMethod('addQueryParameters');
        $method->setAccessible(true);

        $uri = $method->invokeArgs($api, [$uri, $queryParameters]);
        $this->assertEquals($expected, $uri);
    }

    /**
     * Data provider for the addQueryParameters() method.
     */
    public function addQueryParametersProvider(): array
    {
        return [
            'no query parameters' => [
                'http://www.example.org',
                [],
                'http://www.example.org',
            ],
            'one query parameter' => [
                'http://www.example.org',
                [
                    'a' => '1',
                ],
                'http://www.example.org?a=1',
            ],
            'two query parameters' => [
                'http://www.example.org',
                [
                    'a' => 1,
                    'b' => 'lorem',
                ],
                'http://www.example.org?a=1&b=lorem',
            ],
            'existing query parameters' => [
                'http://www.example.org?e=x',
                [
                    'a' => 1,
                    'b' => 'lorem',
                ],
                'http://www.example.org?e=x&a=1&b=lorem',
            ],
            'escaping query parameters' => [
                'http://www.example.org',
                [
                    'a' => 'lorem&ipsum',
                ],
                'http://www.example.org?a=lorem%26ipsum',
            ]
        ];
    }

    /**
     * Tests the getMultipartStream() method.
     */
    public function testGetMultipartStream(): void
    {
        $api = $this->getApiBaseMock();
        $reflection = new \ReflectionClass($api);
        $method = $reflection->getMethod('getMultipartStream');
        $method->setAccessible(true);

        $stream = $method->invokeArgs($api, [
            [
                'payload1' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida orci ut magna ornare interdum.',
                'payload2' => 'Pellentesque hendrerit nibh placerat, posuere ipsum nec, feugiat turpis.',
            ]
        ]);

        $this->assertInstanceOf(MultipartStream::class, $stream);
        $boundary = $stream->getBoundary();
        $expected = "--$boundary\r\n"
        . "Content-Type: application/json\r\n"
        . "Content-Disposition: form-data; name=\"payload1\"; filename=\"blob\"\r\n"
        . "Content-Length: 99\r\n\r\n"
        . "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed gravida orci ut magna ornare interdum.\r\n"
        . "--$boundary\r\n"
        . "Content-Type: application/json\r\n"
        . "Content-Disposition: form-data; name=\"payload2\"; filename=\"blob\"\r\n"
        . "Content-Length: 72\r\n\r\n"
        . "Pellentesque hendrerit nibh placerat, posuere ipsum nec, feugiat turpis.\r\n"
        . "--$boundary--\r\n";
        $this->assertEquals($expected, $stream->getContents());
    }

    /**
     * Creates a mocked version of the ApiBase class.
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     *   The mocked class.
     */
    protected function getApiBaseMock(): MockObject
    {
        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $api = $this->getMockBuilder(ApiBase::class)
            ->setConstructorArgs([$client])
            ->getMockForAbstractClass();
        return $api;
    }
}
