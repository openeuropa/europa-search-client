<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use OpenEuropa\EuropaSearchClient\Api\ApiBase;
use OpenEuropa\EuropaSearchClient\ClientInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests the API base class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\ApiBase
 */
class ApiBaseTest extends TestCase
{

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
        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $api = $this->getMockBuilder(ApiBase::class)
            ->setConstructorArgs([$client])
            ->getMockForAbstractClass();
        $reflection = new \ReflectionClass($api);
        $method = $reflection->getMethod('addQueryParameters');
        $method->setAccessible(true);

        $uri = $method->invokeArgs($api, [ $uri, $queryParameters]);
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
}
