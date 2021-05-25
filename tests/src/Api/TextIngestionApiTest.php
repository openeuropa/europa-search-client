<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

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
        $actualResult = $this->getTestingClient($clientConfig, $responses, [$this, 'inspectRequest'])
            ->ingestText(
                'http://example.com',
                'The quick brown fox jumps over the lazy dog',
                ['en', 'ro'],
                [
                    'field1' => ['value1', 'value2'],
                    'field2' => ['value3', 2345],
                ],
                'unique-my-ID',
            );
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param RequestInterface $request
     */
    public function inspectRequest(RequestInterface $request): void
    {
        if ($request->getUri() == 'http://example.com/token') {
            $this->assertSame('grant_type=client_credentials', $request->getBody()->getContents());
        } else {
            $this->assertEquals('http://example.com/ingest/text?apiKey=bananas&database=cucumbers&uri=http%3A%2F%2Fexample.com&language=%5B%22en%22%2C%22ro%22%5D&reference=unique-my-ID', $request->getUri());
            $this->assertSame('Bearer JWT_TOKEN', $request->getHeaderLine('Authorization'));
            $this->assertSame('JWT_TOKEN', $request->getHeaderLine('Authorization-propagation'));

            // Detect the boundary.
            preg_match('/;boundary="([^"].*)"/', $request->getHeaderLine('Content-Type'), $found);
            $boundary = $found[1];
            $this->assertSame('multipart/form-data;boundary="' . $boundary . '"', $request->getHeaderLine('Content-Type'));

            $parts = explode("--{$boundary}", $request->getBody()->getContents());
            array_shift($parts);
            array_pop($parts);

            [$headers, $content] = explode("\r\n\r\n", $parts[0]);

            // @todo Inspect the headers & content of each part. Maybe it's
            //   worth it to create a trait to be reused in each test.
        }
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
