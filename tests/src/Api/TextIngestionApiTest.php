<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\TextIngestionApi
 */
class TextIngestionApiTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::ingest
     * @dataProvider providerTestTextIngestion
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testTextIngestion(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)
            ->ingestText('http://example.com');
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @see self::testTextIngestion
     * @return array
     */
    public function providerTestTextIngestion(): array
    {
        return [
            'ingestion' => [
                [
                    'apiKey' => 'bananas',
                    'database' => 'cucumbers',
                    'textIngestionApiEndpoint' => 'http://example.com/ingest/text',
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
