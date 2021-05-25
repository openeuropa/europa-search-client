<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\FileIngestionApi
 */
class FileIngestionApiTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::ingest
     * @dataProvider providerTestFileIngestion
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testFileIngestion(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)
            ->ingestFile(
                'http://example.com',
                file_get_contents(__DIR__ . '/../../fixtures/files/image.png')
            );
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @see self::testFileIngestion
     * @return array
     */
    public function providerTestFileIngestion(): array
    {
        return [
            'ingestion' => [
                [
                    'apiKey' => 'bananas',
                    'database' => 'cucumbers',
                    'fileIngestionApiEndpoint' => 'http://example.com/ingest/text',
                    'tokenApiEndpoint' => 'http://example.com/token',
                    'consumerKey' => 'baz',
                    'consumerSecret' => 'qux',

                ],
                [
                    new Response(200, [], json_encode([
                        'access_token' => 'JWT_TOKEN',
                        'scope' => 'APPLICATION_SCOPE',
                        'token_type' => 'Bearer',
                        'expires_in' => 3600,
                    ])),
                    new Response(200, [], json_encode([
                        'apiVersion' => '2.67',
                        'reference' => 'foo',
                        'trackingId' => 'bar',
                    ])),
                ],
                (new Ingestion())
                    ->setApiVersion('2.67')
                    ->setReference('foo')
                    ->setTrackingId('bar'),
            ],
        ];
    }
}
