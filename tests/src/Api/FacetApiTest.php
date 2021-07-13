<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Api\FacetApi;
use OpenEuropa\EuropaSearchClient\Exception\ParameterValueException;
use OpenEuropa\EuropaSearchClient\Model\Facets;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetTestGeneratorTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Tests the facet API.
 */
class FacetApiTest extends TestCase
{
    use ClientTestTrait;
    use FacetTestGeneratorTrait;
    use InspectTestRequestTrait;

    /**
     * Tests set sort invalid parameter.
     */
    public function testSetSortInvalidParameter(): void
    {
        $object = new FacetApi();
        $class = new \ReflectionClass($object);
        $method = $class->getMethod('setFacetSort');
        $exception = new ParameterValueException("::setFacetSort() received invalid argument 'Invalid argument', must be one of 'DATE', 'REVERSE_DATE', 'ALPHABETICAL', 'REVERSE_ALPHABETICAL', 'DOCUMENT_COUNT', 'REVERSE_DOCUMENT_COUNT', 'NUMBER_DECREASING', 'NUMBER_INCREASING'.");
        $this->expectExceptionObject($exception);
        $method->invokeArgs($object, ['Invalid argument']);
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
        $actualResult = $this->getTestingClient($clientConfig, $responses, [$this, 'inspectRequest'])
            ->getFacets(
                'whatever',
                ['en', 'de'],
                'en',
                ['term' => ['DMAKE_ES_EVENT_TYPE_NAME' => 'ADOPTION_DISTRIBUTE']],
                'ALPHABETICAL',
                '21edswq223rews'
            );
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param RequestInterface $request
     */
    public function inspectRequest(RequestInterface $request): void
    {
        $this->assertEquals('http://example.com/facet?apiKey=foo&database=qux&text=whatever&sessionToken=21edswq223rews&sort=ALPHABETICAL', $request->getUri());
        $boundary = $this->getBoundary($request);
        $this->inspectBoundary($request, $boundary);
        $parts = $this->getMultiParts($request, $boundary);
        $this->assertCount(3, $parts);
        $this->inspectPart($parts[0], 'application/json', 'languages', 11, '["en","de"]');
        $this->inspectPart($parts[1], 'application/json', 'query', 59, '{"term":{"DMAKE_ES_EVENT_TYPE_NAME":"ADOPTION_DISTRIBUTE"}}');
        $this->inspectPart($parts[2], 'application/json', 'displayLanguage', 4, '"en"');
    }

    /**
     * @return array
     * @see self::testGetFacets
     */
    public function providerTestGetFacets(): array
    {
        [$facetsAsArray, $facetsAsObject] = $this->generateTestingFacetItems(5);

        return [
            'simple facet request' => [
                [
                    'apiKey' => 'foo',
                    'database' => 'qux',
                    'facetApiEndpoint' => 'http://example.com/facet',
                ],
                [
                    new Response(200, [], json_encode([
                        'apiVersion' => '1.34.0',
                        'facets' => $facetsAsArray,
                        'terms' => 'foo',
                    ])),
                ],
                (new Facets())
                    ->setApiVersion('1.34.0')
                    ->setFacets($facetsAsObject)
                    ->setTerms('foo'),
            ],
        ];
    }
}
