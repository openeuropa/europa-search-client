<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Document;
use OpenEuropa\EuropaSearchClient\Model\QueryLanguage;
use OpenEuropa\EuropaSearchClient\Model\Search;
use PHPUnit\Framework\TestCase;

/**
 * Tests the search model class.
 *
 * @covers \OpenEuropa\EuropaSearchClient\Model\Search
 */
class SearchTest extends TestCase
{

    /**
     * Tests the setters and getters.
     */
    public function testSettersAndGetters(): void
    {
        $model = new Search();
        $model->setApiVersion('2.31');
        $model->setBestBets([]);
        $model->setGroupByField('group_1');
        $model->setPageNumber(1);
        $model->setPageSize(5);
        $queryLanguage = new QueryLanguage();
        $model->setQueryLanguage($queryLanguage);
        $model->setResponseTime(160);
        $results = [
            new Document(),
            new Document(),
            new Document(),
        ];
        $model->setResults($results);
        $model->setSort('relevance');
        $model->setSpellingSuggestion(null);
        $model->setTerms('***');
        $model->setTotalResults(153);
        $warnings = [
            'Warning 1',
            'Warning 2',
        ];
        $model->setWarnings($warnings);

        $this->assertEquals('2.31', $model->getApiVersion());
        $this->assertEquals([], $model->getBestBets());
        $this->assertEquals('group_1', $model->getGroupByField());
        $this->assertEquals(1, $model->getPageNumber());
        $this->assertEquals(5, $model->getPageSize());
        $this->assertSame($queryLanguage, $model->getQueryLanguage());
        $this->assertEquals(160, $model->getResponseTime());
        $this->assertSame($results, $model->getResults());
        $this->assertEquals('relevance', $model->getSort());
        $this->assertEquals(null, $model->getSpellingSuggestion());
        $this->assertEquals('***', $model->getTerms());
        $this->assertEquals(153, $model->getTotalResults());
        $this->assertEquals($warnings, $model->getWarnings());
    }
}
