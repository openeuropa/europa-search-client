<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Facets;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\FacetTestGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\FacetApi
 */
class FacetApiTest extends TestCase
{
    use ClientTestTrait;
    use FacetTestGeneratorTrait;

    /**
     * @covers ::getFacets
     * @dataProvider providerTestGetFacets
     *
     * @param array $clientConfig
     * @param mixed $response
     * @param mixed $expectedResult
     */
    public function testGetFacets(array $clientConfig, $response, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, [$response])
            ->getFacets('whatever');
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @return array
     * @see self::testGetFacets
     */
    public function providerTestGetFacets(): array
    {
        [$facetsAsArray, $facetsAsObject] = $this->generateTestingFacetItems(5);
        $facetValues = [];
        for ($i = 0; $i < 5; $i++) {
            $facetValues[] = [
                'apiVersion' => str_shuffle(md5(serialize($facetValues))),
                'count' => rand(30, 3000),
                'rawValue' => str_shuffle(md5(serialize($facetValues))),
                'value' => str_shuffle(md5(serialize($facetValues))),
            ];
        }

        return [
            'simple facet request' => [
                [
                    'apiKey' => 'foo',
                    'facetApiEndpoint' => 'http://example.com/facet',
                ],
                new Response(200, [], json_encode([
                    'apiVersion' => '1.34.0',
                    'facets' => $facetsAsArray,
                    'terms' => 'foo',
                ])),
                (new Facets())
                    ->setApiVersion('1.34.0')
                    ->setFacets($facetsAsObject)
                    ->setTerms('foo'),
            ],
        ];
    }
}
