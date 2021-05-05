<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Api\SearchApi;
use OpenEuropa\EuropaSearchClient\Model\Search;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the SearchApi class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\SearchApi
 */
class SearchApiTest extends ApiTest
{

    /**
     * Tests the search() method.
     *
     * @dataProvider provideTestSearchScenarios
     *
     * @param array $parameters
     *   The request parameters:
     * @param Search $expected
     *   The expected value.
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testSearch(array $parameters, array $expected, ResponseInterface $response)
    {
        $http_client = $this->getHttpClientMock($response);
        $client = $this->getSearchClientMock($http_client);
        $search_api = new SearchApi($client);
        $actual_object = $search_api->search($parameters);
        $expected_object = $this->getSerializer()->denormalize($expected, Search::class);

        $this->assertEquals($expected_object, $actual_object);
    }

    /**
     * Data provider for the testSearch() method.
     */
    public function provideTestSearchScenarios(): array
    {
        $query_class = $this->getMockBuilder(\JsonSerializable::class)->getMock();
        $query_class->method('jsonSerialize')
            ->willReturn($this->returnValue([
                'term' => ['Value1']
            ]));

        $expected = [
            'simple text search' => [
                'apiVersion' => '2.69',
                'terms' => 'test1',
                'responseTime' => 1,
                'totalResults' => 1,
                'pageNumber' => 1,
                'pageSize' => 50,
                'results' => [
                    [
                        'apiVersion' => '2.69',
                        'reference' => 'ref1',
                        'url' => 'https://example.com',
                        'contentType' => 'contentType',
                        'content' => 'this is a test',
                    ]
                ],
            ],
            'advanced query search' => [
                'apiVersion' => '2.69',
                'terms' => 'test2',
                'responseTime' => 2,
                'totalResults' => 1,
                'pageNumber' => 1,
                'pageSize' => 10,
                'results' => [
                    [
                        'apiVersion' => '2.69',
                        'reference' => 'ref2',
                        'url' => 'https://example2.com',
                        'contentType' => 'contentType',
                        'content' => 'this is advanced test',
                    ]
                ],
            ],
        ];
        return [
            'simple text search' => [
                [
                    'apiKey' => 'test_api_key',
                    'text' => 'test1',
                ],
                $expected['simple text search'],
                new Response(200, [], json_encode($expected['simple text search'])),
            ],
            'advanced query search' => [
                [
                    'apiKey' => 'test_api_key',
                    'text' => 'test2',
                    'query' => $query_class,
                ],
                $expected['advanced query search'],
                new Response(200, [], json_encode($expected['advanced query search'])),
            ],
        ];
    }
}
