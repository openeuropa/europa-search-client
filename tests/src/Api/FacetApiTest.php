<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Api\FacetApi;
use OpenEuropa\EuropaSearchClient\Model\FacetCollection;
use OpenEuropa\EuropaSearchClient\Model\FacetValue;
use Psr\Http\Message\ResponseInterface;

/**
 * Tests the FacetApi class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Api\FacetApi
 */
class FacetApiTest extends ApiTest
{

    /**
     * Tests the query() method.
     *
     * @dataProvider provideTestSearchScenarios
     *
     * @param array $parameters
     *   The request parameters:
     * @param FacetCollection $expected
     *   The expected value.
     * @param \Psr\Http\Message\ResponseInterface $response
     *   The http response.
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function testQuery(array $parameters, array $expected, ResponseInterface $response)
    {
        $http_client = $this->getHttpClientMock($response);
        $client = $this->getSearchClientMock($http_client);
        $facet_api = new FacetApi($client);
        $actual_object = $facet_api->query($parameters);
        $expected_object = $this->getSerializer()->denormalize($expected, FacetValue::class);

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
            'facet search' => [
                'apiVersion' => '2.31',
                'facets' => [
                    [
                        'database' => 'database1',
                        'count' => 0,
                        'name' => 'name1',
                        'rawName' => 'rawname1',
                        'values' => []
                    ]
                ],
                'terms' => 'test1',
            ]
        ];
        return [
            'facet search' => [
                [
                    'apiKey' => 'test_api_key',
                    'text' => 'test1',
                ],
                $expected['facet search'],
                new Response(200, [], json_encode($expected['facet search'])),
            ]
        ];
    }
}
