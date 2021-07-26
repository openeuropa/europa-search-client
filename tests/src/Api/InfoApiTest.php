<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Info;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\InfoApi
 */
class InfoApiTest extends TestCase
{
    use ClientTestTrait;
    use InspectTestRequestTrait;

    /**
     * @dataProvider providerTestGetInfo
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testGetInfo(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)->getInfo();
        $this->assertEquals($expectedResult, $actualResult);
        $this->assertCount(1, $this->clientHistory);
        $request = $this->clientHistory[0]['request'];
        $this->assertEquals('http://example.com/search/info', $request->getUri());
    }

    /**
     * @see self::testGetInfo()
     */
    public function providerTestGetInfo(): array
    {
        return [
            'info' => [
                [
                    'infoApiEndpoint' => 'http://example.com/search/info',
                ],
                [
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/info_response.json'))
                ],
                (new Info())
                    ->setGroupId('eu.europa.ec.digit.search.webapp')
                    ->setComment('version=2.69')
                    ->setArtifactId('search-api'),
            ]
        ];
    }
}
