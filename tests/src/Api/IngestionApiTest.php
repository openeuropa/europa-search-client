<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Api\IngestionApi;
use OpenEuropa\EuropaSearchClient\ClientInterface;
use OpenEuropa\EuropaSearchClient\Model\Ingestion;
use OpenEuropa\EuropaSearchClient\Model\MetadataCollection;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the IngestionApi class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\IngestionApi
 *
 */
class IngestionApiTest extends ApiTest
{
    /**
     * Test the setToken method.
     */
    public function testSetToken()
    {
        $client = $this->getMockBuilder(ClientInterface::class)->getMock();
        $ingestionApi = new IngestionApi($client);
        $ingestionApi->setToken('test_jwt');
        $reflection = new \ReflectionClass($ingestionApi);
        $property = $reflection->getProperty('request_headers');
        $property->setAccessible(true);

        $expected = [
            'Authorization' => 'Bearer test_jwt',
            'Authorization-propagation' => 'test_jwt',
        ];

        $this->assertEquals($expected, $property->getValue($ingestionApi));
    }

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
        $client = $this->getSearchClientMock($http_client);
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
                [],
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
        $client = $this->getSearchClientMock($http_client);
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
                    'metadata' => (new MetadataCollection())->addMetadata('Field1', ['Value1']),
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
        $client = $this->getSearchClientMock($http_client);
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
}
