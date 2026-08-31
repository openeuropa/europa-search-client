<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\IngestionTracking;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\AssertTestRequestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Endpoint\IngestionTrackingEndpoint
 */
class IngestionTrackingEndpointTest extends TestCase
{
    use ClientTestTrait;
    use AssertTestRequestTrait;

    /**
     * @dataProvider providerTestIngestionTracking
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testIngestionTracking(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)
            ->getIngestionTrackingStatuses(["48134d43-df3f-4a5a-b420-0595237e970b", "48134d43-df3f-4a5a-b420-0595237e970c"]);
        $this->assertEquals($expectedResult, $actualResult);
        $this->assertCount(2, $this->clientHistory);
        $request = $this->clientHistory[0]['request'];
        $this->assertTokenRequest($request);
        $request = $this->clientHistory[1]['request'];
        $this->assertEquals('http://example.com/ingestion/track/batch', $request->getUri());
        $this->assertAuthorizationHeaders($request);
    }

    /**
     * @see self::testIngestionTracking
     * @return array
     */
    public function providerTestIngestionTracking(): array
    {
        return [
            'ingestionTracking' => [
                [
                    'apiKey' => 'bananas',
                    'database' => 'cucumbers',
                    'ingestionTrackingEndpoint' => 'http://example.com/ingestion/track/batch',
                    'tokenApiEndpoint' => 'http://example.com/token',
                    'consumerKey' => 'baz',
                    'consumerSecret' => 'qux',

                ],
                [
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/jwt_response.json')),
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/ingestion_tracking_response.json')),
                ],
                [
                    (new IngestionTracking())
                        ->setApiVersion('2.67')
                        ->setTrackingId('48134d43-df3f-4a5a-b420-0595237e970b')
                        ->setStatus('FINISHED')
                        ->setMessage('msg1'),
                    (new IngestionTracking())
                        ->setApiVersion('2.67')
                        ->setTrackingId('48134d43-df3f-4a5a-b420-0595237e970c')
                        ->setStatus('PROCESSING')
                        ->setMessage('msg2'),
                ],
            ],
        ];
    }
}
