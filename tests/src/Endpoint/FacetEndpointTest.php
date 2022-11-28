<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Endpoint;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Endpoint\FacetEndpoint;
use OpenEuropa\EuropaSearchClient\Exception\ParameterValueException;
use OpenEuropa\EuropaSearchClient\Model\Facets;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetTestGeneratorTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\AssertTestRequestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Endpoint\FacetEndpoint
 */
class FacetEndpointTest extends TestCase
{
    use ClientTestTrait;
    use FacetTestGeneratorTrait;
    use AssertTestRequestTrait;

    public function testSetSortInvalidParameter(): void
    {
        $endpoint = new FacetEndpoint('http://example.com/facet', [
            'apiKey' => 'foo',
            'database' => 'qux',
        ]);
        $exception = new ParameterValueException("::setFacetSort() received invalid argument 'Invalid argument', must be one of 'DATE', 'REVERSE_DATE', 'ALPHABETICAL', 'REVERSE_ALPHABETICAL', 'DOCUMENT_COUNT', 'REVERSE_DOCUMENT_COUNT', 'NUMBER_DECREASING', 'NUMBER_INCREASING'.");
        $this->expectExceptionObject($exception);
        $endpoint->setFacetSort('Invalid argument');
    }

    /**
     * @dataProvider providerTestGetFacets
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testGetFacets(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses)
            ->getFacets(
                'whatever',
                ['en', 'de'],
                'en',
                ['term' => ['DMAKE_ES_EVENT_TYPE_NAME' => 'ADOPTION_DISTRIBUTE']],
                'ALPHABETICAL',
                '21edswq223rews',
                ['DMAKE_ES_EVENT_TYPE_NAME']
            );
        $this->assertEquals($expectedResult, $actualResult);
        $this->assertCount(1, $this->clientHistory);
        $request = $this->clientHistory[0]['request'];
        $this->assertEquals('http://example.com/facet?apiKey=foo&database=qux&text=whatever&sessionToken=21edswq223rews&sort=ALPHABETICAL', $request->getUri());
        $boundary = $this->getRequestBoundary($request);
        $this->assertBoundary($request, $boundary);
        $parts = $this->getRequestMultipartStreamResources($request, $boundary);
        $this->assertCount(4, $parts);
        $this->assertMultipartStreamResource($parts[0], 'application/json', 'languages', 11, '["en","de"]');
        $this->assertMultipartStreamResource($parts[1], 'application/json', 'query', 59, '{"term":{"DMAKE_ES_EVENT_TYPE_NAME":"ADOPTION_DISTRIBUTE"}}');
        $this->assertMultipartStreamResource($parts[2], 'application/json', 'displayLanguage', 4, '"en"');
        $this->assertMultipartStreamResource($parts[3], 'application/json', 'displayFields', 28, '["DMAKE_ES_EVENT_TYPE_NAME"]');
    }

    /**
     * @return array
     * @see self::testGetFacets
     */
    public function providerTestGetFacets(): array
    {
        $facetsAsArray = $this->generateTestingFacetItemsAsArray(5);
        $facetsAsObject = $this->convertTestingFacetItemsToObjects($facetsAsArray);
        $response = json_decode(file_get_contents(__DIR__ . '/../../fixtures/json/simple_facet_response.json'), true);
        $response['facets'] = $facetsAsArray;
        return [
            'simple facet request' => [
                [
                    'apiKey' => 'foo',
                    'database' => 'qux',
                    'facetApiEndpoint' => 'http://example.com/facet',
                ],
                [
                    new Response(200, [], json_encode($response)),
                ],
                (new Facets())
                    ->setApiVersion('1.34.0')
                    ->setFacets($facetsAsObject)
                    ->setTerms('foo'),
            ],
        ];
    }
}
