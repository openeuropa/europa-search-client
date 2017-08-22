<?php

/**
 * @file
 * EC\EuropaSearch\Tests\Proxies\Search\SearchDataProvider.
 */

namespace EC\EuropaSearch\Tests\Proxies\Search;

use EC\EuropaSearch\Messages\Search\SearchMessage;
use EC\EuropaSearch\Messages\Search\SearchRequest;
use EC\EuropaSearch\Tests\Messages\Search\Filters\Combined\BooleanQueryDataProvider;

/**
 * Class SearchDataProvider.
 *
 * Provides data for content searching related tests.
 *
 * @package EC\EuropaSearch\Tests\Proxies\Search
 */
class SearchDataProvider
{

    /**
     * Provides objects necessary for the test.
     *
     * @return array
     *   The objects for the test:
     *   - 'submitted':  IndexedDocument to convert in the test;
     *   - 'expected' :   Excepted IndexingRequest at the end of the test;
     */
    public function searchRequestProvider()
    {

        $searchMessage = new SearchMessage();
        $searchMessage->setSearchedLanguages(['en', 'fr']);
        $searchMessage->setHighLightParameters('<strong>{}</strong>', 250);
        $searchMessage->setPagination(1, 20);
        $searchMessage->setSearchedText('text to search');
        $searchMessage->setSortCriteria('field', SearchMessage::SEARCH_SORT_DESC);
        $searchMessage->setSessionToken('123456');

        $booleanProvider = new BooleanQueryDataProvider();
        $searchQuery = $booleanProvider->getValidNestedBooleanQuery();
        $searchMessage->setQuery($searchQuery);


        $searchRequest = new SearchRequest();
        $searchRequest->setLanguages(['en', 'fr']);
        $searchRequest->setHighlightRegex('<strong>{}</strong>');
        $searchRequest->setHighlightLimit(250);
        $searchRequest->setPageNumber(1);
        $searchRequest->setPageSize(20);
        $searchRequest->setText('text to search');
        $searchRequest->setSort('filed:DESC');
        $searchRequest->setSessionToken('123456');
        $searchRequest->setAPIKey('abcd1234');

        $fileContent = file_get_contents(__DIR__.'/fixtures/json_sample.json');

        $searchRequest->setQueryJSON($fileContent);

        return [
            'submitted' => $searchMessage,
            'expected' => $searchRequest,
        ];
    }
}
