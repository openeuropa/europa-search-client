<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Info;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\InfoApi
 */
class InfoApiTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::getInfo
     * @dataProvider providerTestGetInfo
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testGetInfo(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)
            ->getInfo();
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @see self::testGetInfo()
     */
    public function providerTestGetInfo(): array
    {
        return [
            'info' => [
                [
                    'infoApiEndpoint' => 'http://example.com/search',
                ],
                [
                    new Response(200, [], json_encode([
                        'groupId' => 'eu.europa.ec.digit.search.webapp',
                        'comment' => 'version=2.69',
                        'artifactId' => 'search-api',
                    ]))
                ],
                (new Info())
                    ->setGroupId('eu.europa.ec.digit.search.webapp')
                    ->setComment('version=2.69')
                    ->setArtifactId('search-api'),
            ]
        ];
    }
}
