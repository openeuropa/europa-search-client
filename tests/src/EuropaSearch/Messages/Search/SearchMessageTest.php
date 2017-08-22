<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\SearchMessageTest.
 */

namespace EC\EuropaSearch\Messages\Search;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use EC\EuropaSearch\Tests\Messages\Search\Filters\Combined\BooleanQueryDataProvider;

/**
 * Class SearchMessageTest.
 *
 * Tests the validation process on SearchMessage.
 *
 * @package EC\EuropaSearch\Messages\Search
 */
class SearchMessageTest extends AbstractEuropaSearchTest
{
    /**
     * Test the SearchMessage validation for a web content (success case).
     */
    public function testSearchMessageValidationSuccess()
    {

        $search = new SearchMessage();
        $search->setSearchedLanguages(['en', 'fr']);
        $search->setHighLightParameters('<strong>{}</strong>', 250);
        $search->setPagination(1, 20);
        $search->setSearchedText('text to search');
        $search->setSortCriteria('field', 'DESC');
        $search->setSessionToken('123456');

        $booleanProvider = new BooleanQueryDataProvider();
        $searchQuery = $booleanProvider->getValidNestedBooleanQuery();
        $search->setQuery($searchQuery);

        $validationErrors = $this->getDefaultValidator()->validate($search);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'SearchMessage validation constraints are not well defined.');
    }

    /**
     * Test the SearchMessage validation for a web content (failure case).
     */
    public function testSearchMessageValidationFailure()
    {

        $search = new SearchMessage();
        $search->setSearchedLanguages(['en', 'zorglub']);
        $search->setHighLightParameters(23, '250');
        $search->setPagination(1.9, '18');
        $search->setSortCriteria('field', 'no rule');
        $search->setSessionToken(true);

        $booleanProvider = new BooleanQueryDataProvider();
        $searchQuery = $booleanProvider->getShouldInvalidNestedBooleanQuery();
        $search->setQuery($searchQuery);

        $validationErrors = $this->getDefaultValidator()->validate($search);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'searchedText' => 'This value should not be blank.',
            'searchedLanguages[1]' => 'This value is not a valid language.',
            'searchQuery.shouldFilterList.filterList[0].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[1].lowerBoundary' => 'The lower boundary is not a valid date.',
            'searchQuery.shouldFilterList.filterList[2].lowerBoundary' => 'The lower boundary is not a valid numeric (int or float).',
            'searchQuery.shouldFilterList.filterList[3].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[5].testedValue' => 'The tested value is not a valid integer.',
            'searchQuery.shouldFilterList.filterList[6].testedValue' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[7].testedValue' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[8].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[11].testedValues[1]' => 'The tested value is not a valid integer.',
            'searchQuery.shouldFilterList.filterList[12].testedValues[0]' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[12].testedValues[1]' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[13].testedValues[0]' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[13].testedValues[1]' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[14].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[0].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[1].lowerBoundary' => 'The lower boundary is not a valid date.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[2].lowerBoundary' => 'The lower boundary is not a valid numeric (int or float).',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[3].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[5].testedValue' => 'The tested value is not a valid integer.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[6].testedValue' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[7].testedValue' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[8].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[11].testedValues[1]' => 'The tested value is not a valid integer.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[12].testedValues[0]' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[12].testedValues[1]' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[13].testedValues[0]' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[13].testedValues[1]' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[16].mustFilterList.filterList[14].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters[5]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters[11]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[1].testedValue' => 'The tested value is not a valid integer.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[2].testedValue' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[3].testedValue' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[4].impliedMetadata.name' => 'This value should be of type string.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[7].testedValues[1]' => 'The tested value is not a valid integer.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[8].testedValues[0]' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[8].testedValues[1]' => 'The tested value is not a valid float.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[9].testedValues[0]' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[9].testedValues[1]' => 'This value is not a valid URL.',
            'searchQuery.shouldFilterList.filterList[17].positiveFilters.filterList[10].impliedMetadata.name' => 'This value should be of type string.',
            'highLightLimit' => 'This value should be of type integer.',
            'paginationLocation' => 'This value should be of type integer.',
            'paginationSize' => 'This value should be of type integer.',
            'sessionToken' => 'This value should be of type string.',
            'highlightRegex' => 'This value should be of type string.',
            'sortDirection' => 'The value you selected is not a valid choice.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'SearchMessage validation constraints are not well defined for: '.$name);
        }
    }
}
