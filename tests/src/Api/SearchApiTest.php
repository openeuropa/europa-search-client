<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Document;
use OpenEuropa\EuropaSearchClient\Model\QueryLanguage;
use OpenEuropa\EuropaSearchClient\Model\Search;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use OpenEuropa\Tests\EuropaSearchClient\Traits\InspectTestRequestTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\SearchApi
 */
class SearchApiTest extends TestCase
{
    use ClientTestTrait;
    use InspectTestRequestTrait;

    /**
     * @covers ::search
     * @dataProvider providerTestSearch
     *
     * @param array $clientConfig
     * @param array $responses
     * @param mixed $expectedResult
     */
    public function testSearch(array $clientConfig, array $responses, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, $responses, [$this, 'inspectRequest'])
            ->search(
                'Programme managers',
                ['en', 'de'],
                ['term' => ['DMAKE_ES_EVENT_TYPE_NAME' => 'ADOPTION_DISTRIBUTE']],
                'es_SortDate',
                'DESC',
                2,
                5,
                '{w+}',
                150,
                '21edswq223rews'
            );
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param RequestInterface $request
     */
    public function inspectRequest(RequestInterface $request): void
    {
        $this->assertEquals('http://example.com/search?apiKey=foo&database=bar&text=Programme+managers&sessionToken=21edswq223rews&pageNumber=2&pageSize=5&highlightRegex=%7Bw%2B%7D&highlightLimit=150', $request->getUri());
        $this->inspectBoundary($request);
        $parts = $this->getMultiParts($request);
        $this->assertCount(3, $parts);
        $this->inspectPart($parts[0], 'application/json', 'languages', 11, '["en","de"]');
        $this->inspectPart($parts[1], 'application/json', 'query', 59, '{"term":{"DMAKE_ES_EVENT_TYPE_NAME":"ADOPTION_DISTRIBUTE"}}');
        $this->inspectPart($parts[2], 'application/json', 'sort', 38, '{"field":"es_SortDate","order":"DESC"}');
    }

    /**
     * @see self::testSearch()
     */
    public function providerTestSearch(): array
    {
        $simpleSearchData = file_get_contents(__DIR__ . '/../../fixtures/json/simple_search.json');
        return [
            'simple search' => [
                [
                    'apiKey' => 'foo',
                    'database' => 'bar',
                    'searchApiEndpoint' => 'http://example.com/search',
                ],
                [
                    new Response(200, [], $simpleSearchData)
                ],
                (new Search())
                    ->setApiVersion('2.69')
                    ->setTerms('"Programme managers"')
                    ->setResponseTime(44)
                    ->setTotalResults(2)
                    ->setPageNumber(1)
                    ->setPageSize(50)
                    ->setSort('title:ASC')
                    ->setGroupByField(null)
                    ->setQueryLanguage(
                        (new QueryLanguage())
                            ->setLanguage('en')
                            ->setProbability(0.0)
                    )
                    ->setSpellingSuggestion('<b>programmes managed</b>')
                    ->setBestBets([])
                    ->setWarnings([])
                    ->setResults([
                        (new Document())
                            ->setApiVersion('2.69')
                            ->setReference('ref1')
                            ->setUrl('http://example.com/ref1')
                            ->setTitle(null)
                            ->setContentType('text/plain')
                            ->setLanguage('en')
                            ->setDatabaseLabel('ACME')
                            ->setDatabase('ACME')
                            ->setSummary(null)
                            ->setWeight(9.849739)
                            ->setGroupById('3')
                            ->setContent('A coordination platform')
                            ->setAccessRestriction(false)
                            ->setPages(null)
                            ->setMetadata([
                                'keywords' => [
                                    '["End-users","Pollution (water, soil), waste disposal and treatm","Water-climate interactions"]',
                                ],
                                'sortStatus' => ['3'],
                                'destination' => [],
                                'type' => ['1'],
                                'title' => ['A coordination platform'],
                            ])
                            ->setChildren([]),
                        (new Document())
                            ->setApiVersion('2.69')
                            ->setReference('ref2')
                            ->setUrl('http://example.com/ref2')
                            ->setTitle(null)
                            ->setContentType('text/plain')
                            ->setLanguage('en')
                            ->setDatabaseLabel('ACME')
                            ->setDatabase('ACME')
                            ->setSummary(null)
                            ->setWeight(9.549583)
                            ->setGroupById('3')
                            ->setContent('Stepping up EU research and innovation cooperation in the water area')
                            ->setAccessRestriction(false)
                            ->setPages(null)
                            ->setMetadata([
                                'keywords' => [
                                    '["Water harvesting","Water resources","Agronomy","Agriculture"]',
                                ],
                                'sortStatus' => ['3'],
                                'destination' => [],
                                'type' => ['1'],
                                'title' => ['EU research in the water area'],
                            ])
                            ->setChildren([]),
                    ]),
            ]
        ];
    }
}
