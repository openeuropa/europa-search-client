<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\DeleteApi
 */
class DeleteApiTest extends TestCase
{
    use ClientTestTrait;
    use InspectTestRequestTrait;

    /**
     * @covers ::deleteDocument
     * @dataProvider providerTestDeleteDocument
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testDeleteDocument(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses, [$this, 'inspectRequest'])
            ->deleteDocument('foo');
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @param RequestInterface $request
     */
    public function inspectRequest(RequestInterface $request): void
    {
        if ($request->getUri() == 'http://example.com/token') {
            $this->inspectTokenRequest($request);
        } else {
            $this->assertEquals('http://example.com/ingest/delete?apiKey=bananas&database=cucumbers&reference=foo', $request->getUri());
            $this->inspectAuthorizationHeaders($request);
        }
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
                true,
            ],
        ];
    }
}
