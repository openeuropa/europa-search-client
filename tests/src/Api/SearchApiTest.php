<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient\Api;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\Document;
use OpenEuropa\EuropaSearchClient\Model\QueryLanguage;
use OpenEuropa\EuropaSearchClient\Model\Search;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\SearchApi
 */
class SearchApiTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::search
     * @dataProvider providerTestSearch
     *
     * @param array $clientConfig
     * @param mixed $response
     * @param mixed $expectedResult
     */
    public function testSearch(array $clientConfig, $response, $expectedResult): void
    {
        $actualResult = $this->getTestingClient($clientConfig, [$response])
            ->search('Programme managers');
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @see self::testSearch()
     */
    public function providerTestSearch(): array
    {
        return [
            'simple search' => [
                [
                    'apiKey' => 'foo',
                    'searchApiEndpoint' => 'http://example.com/search',
                ],
                new Response(200, [], json_encode([
                    'apiVersion' => '2.69',
                    'terms' => '"Programme managers"',
                    'responseTime' => 44,
                    'totalResults' => 2,
                    'pageNumber' => 1,
                    'pageSize' => 50,
                    'sort' => 'title:ASC',
                    'groupByField' => null,
                    'queryLanguage' => [
                        'language' => 'en',
                        'probability' => 0.0,
                    ],
                    'spellingSuggestion' => '<b>programmes managed</b>',
                    'bestBets' => [
                    ],
                    'results' => [
                        [
                            'apiVersion' => '2.69',
                            'reference' => 'ref1',
                            'url' => 'http://example.com/ref1',
                            'title' => null,
                            'contentType' => 'text/plain',
                            'language' => 'en',
                            'databaseLabel' => 'ACME',
                            'database' => 'ACME',
                            'summary' => null,
                            'weight' => 9.849739,
                            'groupById' => '3',
                            'content' => 'A coordination platform',
                            'accessRestriction' => false,
                            'pages' => null,
                            'metadata' => [
                                'keywords' => [
                                    0 => '["End-users","Pollution (water, soil), waste disposal and treatm","Water-climate interactions"]',
                                ],
                                'sortStatus' => [
                                    0 => '3',
                                ],
                                'destination' => [
                                ],
                                'type' => [
                                    0 => '1',
                                ],
                                'title' => [
                                    0 => 'A coordination platform',
                                ],
                            ],
                            'children' => [
                            ],
                        ],
                        [
                            'apiVersion' => '2.69',
                            'reference' => 'ref2',
                            'url' => 'http://example.com/ref2',
                            'title' => null,
                            'contentType' => 'text/plain',
                            'language' => 'en',
                            'databaseLabel' => 'ACME',
                            'database' => 'ACME',
                            'summary' => null,
                            'weight' => 9.549583,
                            'groupById' => '3',
                            'content' => 'Stepping up EU research and innovation cooperation in the water area',
                            'accessRestriction' => false,
                            'pages' => null,
                            'metadata' => [
                                'keywords' => [
                                    0 => '["Water harvesting","Water resources","Agronomy","Agriculture"]',
                                ],
                                'sortStatus' => [
                                    0 => '3',
                                ],
                                'destination' => [
                                ],
                                'type' => [
                                    0 => '1',
                                ],
                                'title' => [
                                    0 => 'EU research in the water area',
                                ],
                            ],
                            'children' => [
                            ],
                        ],
                    ],
                    'warnings' => [
                    ],
                ])),
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