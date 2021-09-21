<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Endpoint\DeleteEndpoint
 */
class DeleteEndpointTest extends TestCase
{
    use ClientTestTrait;
    use InspectTestRequestTrait;

    /**
     * @dataProvider providerTestDeleteDocument
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testDeleteDocument(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)
            ->deleteDocument('foo');
        $this->assertSame($expectedResult, $actualResult);
        $this->assertCount(2, $this->clientHistory);
        $request = $this->clientHistory[0]['request'];
        $this->inspectTokenRequest($request);
        $request = $this->clientHistory[1]['request'];
        $this->assertEquals('http://example.com/ingest/delete?apiKey=bananas&database=cucumbers&reference=foo', $request->getUri());
        $this->inspectAuthorizationHeaders($request);
    }

    /**
     * @see self::testDeleteDocument
     * @return array
     */
    public function providerTestDeleteDocument(): array
    {
        return [
            'delete' => [
                [
                    'apiKey' => 'bananas',
                    'database' => 'cucumbers',
                    'deleteApiEndpoint' => 'http://example.com/ingest/delete',
                    'tokenApiEndpoint' => 'http://example.com/token',
                    'consumerKey' => 'baz',
                    'consumerSecret' => 'qux',

                ],
                [
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/jwt_response.json')),
                    new Response(200, [], file_get_contents(__DIR__ . '/../../fixtures/json/delete_document_response.json')),
                ],
                true,
            ],
        ];
    }
}
