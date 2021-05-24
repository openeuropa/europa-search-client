<?php

declare(strict_types = 1);

namespace OpenEuropa\Tests\EuropaSearchClient\Model;

use OpenEuropa\EuropaSearchClient\Model\Document;
use OpenEuropa\EuropaSearchClient\Model\QueryLanguage;
use OpenEuropa\EuropaSearchClient\Model\Search;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass  \OpenEuropa\EuropaSearchClient\Model\Search
 */
class SearchTest extends TestCase
{

    /**
     * @covers ::setApiVersion
     * @covers ::getApiVersion
     * @covers ::setBestBets
     * @covers ::getBestBets
     * @covers ::setGroupByField
     * @covers ::getGroupByField
     * @covers ::setPageNumber
     * @covers ::getPageNumber
     * @covers ::setPageSize
     * @covers ::getPageSize
     * @covers ::setQueryLanguage
     * @covers ::getQueryLanguage
     * @covers ::setResponseTime
     * @covers ::getResponseTime
     * @covers ::setResults
     * @covers ::getResults
     * @covers ::setSort
     * @covers ::getSort
     * @covers ::setSpellingSuggestion
     * @covers ::getSpellingSuggestion
     * @covers ::setTerms
     * @covers ::getTerms
     * @covers ::setTotalResults
     * @covers ::getTotalResults
     * @covers ::setWarnings
     * @covers ::getWarnings
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

        $this->assertSame('2.31', $model->getApiVersion());
        $this->assertSame([], $model->getBestBets());
        $this->assertSame('group_1', $model->getGroupByField());
        $this->assertSame(1, $model->getPageNumber());
        $this->assertSame(5, $model->getPageSize());
        $this->assertSame($queryLanguage, $model->getQueryLanguage());
        $this->assertSame(160, $model->getResponseTime());
        $this->assertSame($results, $model->getResults());
        $this->assertSame('relevance', $model->getSort());
        $this->assertNull($model->getSpellingSuggestion());
        $this->assertSame('***', $model->getTerms());
        $this->assertSame(153, $model->getTotalResults());
        $this->assertSame($warnings, $model->getWarnings());
    }
}
