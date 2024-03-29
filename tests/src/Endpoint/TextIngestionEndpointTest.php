<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\AssertTestRequestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Endpoint\TextIngestionEndpoint
 */
class TextIngestionEndpointTest extends TestCase
{
    use ClientTestTrait;
    use AssertTestRequestTrait;

    /**
     * @dataProvider providerTestTextIngestion
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testTextIngestion(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)
            ->ingestText(
                'http://example.com',
                'The quick brown fox jumps over the lazy dog',
                ['en', 'ro'],
                [
                    'field1' => ['value1', 'value2'],
                    'field2' => ['value3', 2345],
                ],
                'unique-my-ID',
                ['user001', 'user02'],
                ['user003', 'user04'],
                ['group001', 'group002'],
                ['group003', 'group004']
            );
        $this->assertEquals($expectedResult, $actualResult);
        $this->assertCount(2, $this->clientHistory);
        $request = $this->clientHistory[0]['request'];
        $this->assertTokenRequest($request);
        $request = $this->clientHistory[1]['request'];
        $this->assertEquals('http://example.com/ingest/text?apiKey=bananas&database=cucumbers&uri=http%3A%2F%2Fexample.com&language=%5B%22en%22%2C%22ro%22%5D&reference=unique-my-ID', $request->getUri());
        $this->assertAuthorizationHeaders($request);
        $boundary = $this->getRequestBoundary($request);
        $this->assertBoundary($request, $boundary);
        $parts = $this->getRequestMultipartStreamResources($request, $boundary);
        $this->assertCount(6, $parts);
        $this->assertMultipartStreamResource($parts[0], 'application/json', 'metadata', 55, '{"field1":["value1","value2"],"field2":["value3",2345]}');
        $this->assertMultipartStreamResource($parts[1], 'application/json', 'aclUsers', 20, '["user001","user02"]');
        $this->assertMultipartStreamResource($parts[2], 'application/json', 'aclNolUsers', 20, '["user003","user04"]');
        $this->assertMultipartStreamResource($parts[3], 'application/json', 'aclGroups', 23, '["group001","group002"]');
        $this->assertMultipartStreamResource($parts[4], 'application/json', 'aclNoGroups', 23, '["group003","group004"]');
        $this->assertMultipartStreamResource($parts[5], 'text/plain', 'text', 43, 'The quick brown fox jumps over the lazy dog');
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
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/jwt_response.json')),
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/text_ingestion_response.json')),
                ],
                (new Ingestion())
                    ->setApiVersion('2.67')
                    ->setReference('foo')
                    ->setTrackingId('bar'),
            ],
        ];
    }
}
