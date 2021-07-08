<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\FileIngestionApi
 */
class FileIngestionApiTest extends TestCase
{
    use ClientTestTrait;
    use InspectTestRequestTrait;

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
        $actualResult = $this->getTestingClient($clientConfig, $responses, [$this, 'inspectRequest'])
            ->ingestFile(
                'http://example.com',
                file_get_contents(__DIR__ . '/../../fixtures/files/image.png'),
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
    }

    /**
     * @param RequestInterface $request
     */
    public function inspectRequest(RequestInterface $request): void
    {
        if ($request->getUri() == 'http://example.com/token') {
            $this->inspectTokenRequest($request);
        } else {
            $this->assertEquals('http://example.com/ingest?apiKey=bananas&database=cucumbers&uri=http%3A%2F%2Fexample.com&language=%5B%22en%22%2C%22ro%22%5D&reference=unique-my-ID', $request->getUri());
            $this->inspectAuthorizationHeaders($request);
            $boundary = $this->getBoundary($request);
            $this->inspectBoundary($request, $boundary);
            $parts = $this->getMultiParts($request, $boundary);
            $this->assertCount(6, $parts);
            $fileData = file_get_contents(__DIR__ . '/../../fixtures/files/image.png');
            $this->inspectPart($parts[0], 'application/json', 'metadata', 55, '{"field1":["value1","value2"],"field2":["value3",2345]}');
            $this->inspectPart($parts[1], 'application/json', 'aclUsers', 20, '["user001","user02"]');
            $this->inspectPart($parts[2], 'application/json', 'aclNolUsers', 20, '["user003","user04"]');
            $this->inspectPart($parts[3], 'application/json', 'aclGroups', 23, '["group001","group002"]');
            $this->inspectPart($parts[4], 'application/json', 'aclNoGroups', 23, '["group003","group004"]');
            $this->inspectPart($parts[5], 'image/png', 'file', 67, $fileData);
        }
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
                    'fileIngestionApiEndpoint' => 'http://example.com/ingest',
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
