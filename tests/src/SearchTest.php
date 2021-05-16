<?php

declare(strict_types=1);

namespace OpenEuropa\Tests\EuropaSearchClient;

use GuzzleHttp\Psr7\Response;
use OpenEuropa\EuropaSearchClient\Model\QueryLanguage;
use OpenEuropa\EuropaSearchClient\Model\SearchResult;
use OpenEuropa\Tests\EuropaSearchClient\Traits\ClientTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \OpenEuropa\EuropaSearchClient\Api\Search
 */
class SearchTest extends TestCase
{
    use ClientTestTrait;

    /**
     * @covers ::search
     */
    public function testSearch(): void
    {
        $client = $this->getTestingClient(
            [
                'apiKey' => 'foo',
                'searchApiEndpoint' => 'http://example.com/search',
            ],
            [new Response(200, [], static::RESPONSE_BODY)]
        );
        $result = $client->search('Programme managers');
        $this->assertInstanceOf(SearchResult::class, $result);
        $this->assertSame('2.69', $result->getApiVersion());
        $this->assertSame('"Programme managers"', $result->getTerms());
        $this->assertSame(44, $result->getResponseTime());
        $this->assertSame(2, $result->getTotalResults());
        $this->assertSame(1, $result->getPageNumber());
        $this->assertSame(50, $result->getPageSize());
        $this->assertSame('title:ASC', $result->getSort());
        $this->assertNull($result->getGroupByField());
        $queryLanguage = (new QueryLanguage())
            ->setLanguage('en')
            ->setProbability(0.0);
        $this->assertEquals($queryLanguage, $result->getQueryLanguage());
        $this->assertSame('<b>programmes managed</b>', $result->getSpellingSuggestion());
        $this->assertSame([], $result->getBestBets());
        $this->assertCount(2, $result->getResults());
        $this->assertIsArray($result->getResults()[0]);
        $this->assertSame([
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
                    '["End-users","Pollution (water, soil), waste disposal and treatm","Water-climate interactions"]',
                ],
                'sortStatus' => ['3'],
                'destination' => [],
                'type' => ['1'],
                'title' => [
                    'A coordination platform',
                ],
            ],
            'children' => [],
        ], $result->getResults()[0]);
        $this->assertIsArray($result->getResults()[0]);
        $this->assertSame([
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
                    '["Water harvesting","Water resources","Agronomy","Agriculture"]',
                ],
                'sortStatus' => ['3'],
                'destination' => [],
                'type' => ['1'],
                'title' => [
                    'EU research in the water area',
                ],
            ],
            'children' => [],
        ], $result->getResults()[1]);
    }

    /**
     * @var string
     */
    const RESPONSE_BODY = <<<Response
{"apiVersion": "2.69",
  "terms": "\"Programme managers\"",
  "responseTime": 44,
  "totalResults": 2,
  "pageNumber": 1,
  "pageSize": 50,
  "sort": "title:ASC",
  "groupByField": null,
  "queryLanguage": {
    "language": "en",
    "probability": 0.0
  },
  "spellingSuggestion": "<b>programmes managed</b>",
  "bestBets": [],
  "results": [
    {
      "apiVersion": "2.69",
      "reference": "ref1",
      "url": "http://example.com/ref1",
      "title": null,
      "contentType": "text/plain",
      "language": "en",
      "databaseLabel": "ACME",
      "database": "ACME",
      "summary": null,
      "weight": 9.849739,
      "groupById": "3",
      "content": "A coordination platform",
      "accessRestriction": false,
      "pages": null,
      "metadata": {
        "keywords": [
          "[\"End-users\",\"Pollution (water, soil), waste disposal and treatm\",\"Water-climate interactions\"]"
        ],
        "sortStatus": [
          "3"
        ],
        "destination": [],
        "type": [
          "1"
        ],
        "title": [
          "A coordination platform"
        ]
      },
      "children": []
    },
    {
      "apiVersion": "2.69",
      "reference": "ref2",
      "url": "http://example.com/ref2",
      "title": null,
      "contentType": "text/plain",
      "language": "en",
      "databaseLabel": "ACME",
      "database": "ACME",
      "summary": null,
      "weight": 9.549583,
      "groupById": "3",
      "content": "Stepping up EU research and innovation cooperation in the water area",
      "accessRestriction": false,
      "pages": null,
      "metadata": {
        "keywords": [
          "[\"Water harvesting\",\"Water resources\",\"Agronomy\",\"Agriculture\"]"
        ],
        "sortStatus": [
          "3"
        ],
        "destination": [],
        "type": [
          "1"
        ],
        "title": [
          "EU research in the water area"
        ]
      },
      "children": []
    }
  ],
  "warnings": []
}
Response;
}
