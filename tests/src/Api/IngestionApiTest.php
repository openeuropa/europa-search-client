<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Http\Factory\Guzzle\RequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use OpenEuropa\EuropaSearchClient\Api\IngestionApi;
use OpenEuropa\EuropaSearchClient\Client;
use OpenEuropa\EuropaSearchClient\ClientInterface;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as HttpClientInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Tests the IngestionApi class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\IngestionApi
 *
 */
class IngestionApiTest extends TestCase
{
    /**
     * The serializer.
     *
     * @var \Symfony\Component\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * Tests the ingestText() method.
     *
     * @dataProvider provideTestIngestTextScenarios
     *
     * @param array $parameters
     *   The request parameters
     * @param array $expected
     *   The expected value.
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     */
    public function testIngestText(string $uri, array $parameters, array $expected, ResponseInterface $response)
    {
        $http_client = $this->getHttpClientMock($response);
        $client = $this->getSearchClient($http_client);
        $ingestionApi = new IngestionApi($client);

        $actual_object = $ingestionApi->ingestText($uri, $parameters);
        $expected_object = $this->getSerializer()->denormalize($expected, Ingestion::class);

        $this->assertEquals($expected_object, $actual_object);
    }

    /**
     * Data provider for the ingestText() method.
     */
    public function provideTestIngestTextScenarios(): array
    {
        $expected = [
            'Ingest a web page' => [
                'apiVersion' => '2.69',
                'reference' => 'ref1',
                'trackingId' => 'test_tracking_id',
            ],
        ];

        return [
            'Ingest a web page' => [
                'http://example.com',
                [
                    'metadata' => ['field1' => 'value1'],
                ],
                $expected['Ingest a web page'],
                new Response(200, [], json_encode($expected['Ingest a web page'])),
            ],
        ];
    }

    /**
     * Tests the ingestFile() method.
     *
     * @dataProvider provideTestIngestFileScenarios
     *
     * @param array $parameters
     *   The request parameters
     * @param array $expected
     *   The expected value.
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     */
    public function testIngestFile(string $uri, array $parameters, array $expected, ResponseInterface $response)
    {
        $http_client = $this->getHttpClientMock($response);
        $client = $this->getSearchClient($http_client);
        $ingestionApi = new IngestionApi($client);

        $actual_object = $ingestionApi->ingestFile($uri, $parameters);
        $expected_object = $this->getSerializer()->denormalize($expected, Ingestion::class);

        $this->assertEquals($expected_object, $actual_object);
    }

    /**
     * Data provider for the ingestFile() method.
     */
    public function provideTestIngestFileScenarios(): array
    {
        $expected = [
            'Ingest a file from uri' => [
                'apiVersion' => '2.69',
                'reference' => 'ref1',
                'trackingId' => 'test_tracking_id',
            ],
        ];

        return [
            'Ingest a file from uri' => [
                'http://example.com',
                [
                    'metadata' => ['field1' => 'value1'],
                ],
                $expected['Ingest a file from uri'],
                new Response(200, [], json_encode($expected['Ingest a file from uri'])),
            ],
        ];
    }

    /**
     * Tests the deleteDocument() method.
     *
     * @dataProvider provideTestDeleteDocumentScenarios
     *
     * @param string $reference
     *   The document reference.
     * @param bool $expected
     *   The expected method return.
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testDeleteDocument(string $reference, bool $expected, ResponseInterface $response)
    {
        $http_client = $this->getHttpClientMock($response);
        $client = $this->getSearchClient($http_client);
        $ingestionApi = new IngestionApi($client);

        $actual = $ingestionApi->deleteDocument($reference);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Data provider for the testDeleteDocument() method.
     */
    public function provideTestDeleteDocumentScenarios(): array
    {
        return [
            'ok' => ['ref1', true, new Response(200)],
            'No Content' => ['ref2', false, new Response(204)],
            'Unauthorized' => ['ref3', false, new Response(401)],
            'Forbidden' => ['ref4', false, new Response(403)],
        ];
    }

    /**
     * Create http client with mock responses.
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     * @return \Psr\Http\Client\ClientInterface
     */
    protected function getHttpClientMock(ResponseInterface $response): HttpClientInterface
    {
        $mock = new MockHandler([$response]);
        $handlerStack = HandlerStack::create($mock);

        return new HttpClient(['handler' => $handlerStack]);
    }

    /**
     * Create a Search client.
     *
     * @param \Psr\Http\Client\ClientInterface $http_client
     *   Http client with mock responses.
     * @return \OpenEuropa\EuropaSearchClient\ClientInterface
     *   Europa search client.
     */
    protected function getSearchClient(HttpClientInterface $http_client): ClientInterface
    {
        // TODO: Mock this client also.
        return new Client($http_client, new RequestFactory(), new StreamFactory(), [
            'apiKey' => 'apiKey',
            'database' => 'database',
            'ingestion_api_endpoint' => 'ingestion_api_endpoint',
            'search_api_endpoint' => 'search_api_endpoint',
        ]);
    }

    /**
     * Returns a configured serializer to convert API responses.
     *
     * @return \Symfony\Component\Serializer\SerializerInterface
     *   The serializer.
     */
    protected function getSerializer(): SerializerInterface
    {
        if ($this->serializer === null) {
            $this->serializer = new Serializer([
                new GetSetMethodNormalizer(null, null, new PhpDocExtractor()),
                new ArrayDenormalizer(),
            ], [
                new JsonEncoder(),
            ]);
        }

        return $this->serializer;
    }
}
