<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

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
            );
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param RequestInterface $request
     */
    public function inspectRequest(RequestInterface $request): void
    {
        if ($request->getUri() == 'http://example.com/token') {
            $this->assertEquals('http://example.com/token', $request->getUri());
            $this->assertSame('Basic YmF6OnF1eA==', $request->getHeaderLine('Authorization'));
            $this->assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
            $this->assertSame('grant_type=client_credentials', $request->getBody()->getContents());
        } else {
            $this->assertEquals('http://example.com/ingest?apiKey=bananas&database=cucumbers&uri=http%3A%2F%2Fexample.com&language=%5B%22en%22%2C%22ro%22%5D&reference=unique-my-ID', $request->getUri());
            $this->assertSame('Bearer JWT_TOKEN', $request->getHeaderLine('Authorization'));
            $this->assertSame('JWT_TOKEN', $request->getHeaderLine('Authorization-propagation'));

            // Detect the boundary.
            preg_match('/; boundary="([^"].*)"/', $request->getHeaderLine('Content-Type'), $found);
            $boundary = $found[1];
            $this->assertSame('multipart/form-data; boundary="' . $boundary . '"', $request->getHeaderLine('Content-Type'));

            $parts = explode("--{$boundary}", $request->getBody()->getContents());
            array_shift($parts);
            array_pop($parts);

            [$headers, $content] = explode("\r\n\r\n", $parts['0']);
            $headers = explode("\r\n", $headers);
            $content = explode("\r\n", $content);
            $this->assertContains('Content-Type: application/json', $headers);
            $this->assertContains('Content-Disposition: form-data; name="metadata"; filename="metadata"', $headers);
            $this->assertContains('Content-Length: 55', $headers);
            $this->assertContains('{"field1":["value1","value2"],"field2":["value3",2345]}', $content);

            [$headers, $content] = explode("\r\n\r\n", $parts[1]);
            $headers = explode("\r\n", $headers);
            $data = file_get_contents(__DIR__ . '/../../fixtures/files/image.png');
            $this->assertContains('Content-Type: image/png', $headers);
            $this->assertContains('Content-Disposition: form-data; name="file"; filename="file"', $headers);
            $this->assertContains('Content-Length: 67', $headers);
            $this->assertSame($data."\r\n", $content);
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
