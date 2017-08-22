<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Combined\AggregatedFilterTest;
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\AggregatedFilters;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class AggregatedFilterTest.
 *
 * Tests the validation process on AggregatedFilter.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Combined
 */
class AggregatedFilterTest extends AbstractEuropaSearchTest
{

    /**
     * Test the AggregatedFilter validation (success case).
     *
     * @param array $aggregatedFilter
     *   Array with a valid AggregatedFilter objects.
     *
     * @dataProvider validAggregatedFilterProvider
     */
    public function testAggregatedFilterValidationSuccess($aggregatedFilter)
    {

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($aggregatedFilter);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'AggregatedFilter validation constraints are not well defined.');
    }

    /**
     * Test the BooleanQuery validation (failure case).
     *
     * @param array $aggregatedFilter
     *   Array with an invalid AggregatedFilter objects.
     * @param array $expectedViolations
     *   Array the expected violation messages indexed by invalid
     *   property path.
     *
     * @dataProvider invalidAggregatedFilterProvider
     */
    public function testAggregatedFilterValidationFailure($aggregatedFilter, $expectedViolations)
    {

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($aggregatedFilter);
        $violations = $this->getViolations($validationErrors);

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expectedViolations[$name], 'AggregatedFilter validation constraints are not well defined');
        }
    }

    /**
     * Provides valid AggregatedFilter for testing.
     *
     * @return  array
     *   Array with a valid AggregatedFilter objects.
     */
    public static function validAggregatedFilterProvider()
    {

        $filterProvider = new SimpleFilterDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();
        $booleanProvider = new BooleanQueryDataProvider();

        $valid = new AggregatedFilters('must');

        $nestedBooleanQuery = $booleanProvider->getValidNestedBooleanQuery();
        $valid->addCombinedQuery($nestedBooleanQuery);

        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $valid->addSimpleFilter($validFilter);
        }

        $boostingQuery = $boostingProvider->getValidBoostingQuery();
        $valid->addCombinedQuery($boostingQuery);

        return [[$valid]];
    }

    /**
     * Provides invalid AggregatedFilter object for testing.
     *
     * @return array
     *   Array with invalid AggregatedFilter objects.
     */
    public static function invalidAggregatedFilterProvider()
    {

        $filterProvider = new SimpleFilterDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();
        $booleanProvider = new BooleanQueryDataProvider();

        $inValid = new AggregatedFilters('must');

        $nestedBooleanQuery = $booleanProvider->getMustInvalidNestedBooleanQuery();
        $inValid->addCombinedQuery($nestedBooleanQuery);

        $validFilters = $filterProvider->getInValidFilters();
        foreach ($validFilters as $validFilter) {
            $inValid->addSimpleFilter($validFilter);
        }

        $boostingQuery = $boostingProvider->getPositiveInValidBoostingQuery();
        $inValid->addCombinedQuery($boostingQuery);

        $expectedViolations = [
            'filterList[0].mustFilterList.filterList[0].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[0].mustFilterList.filterList[1].lowerBoundary' => 'The lower boundary is not a valid date.',
            'filterList[0].mustFilterList.filterList[2].lowerBoundary' => 'The lower boundary is not a valid numeric (int or float).',
            'filterList[0].mustFilterList.filterList[3].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[0].mustFilterList.filterList[5].testedValue' => 'The tested value is not a valid integer.',
            'filterList[0].mustFilterList.filterList[6].testedValue' => 'The tested value is not a valid float.',
            'filterList[0].mustFilterList.filterList[7].testedValue' => 'This value is not a valid URL.',
            'filterList[0].mustFilterList.filterList[8].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[0].mustFilterList.filterList[11].testedValues[1]' => 'The tested value is not a valid integer.',
            'filterList[0].mustFilterList.filterList[12].testedValues[0]' => 'The tested value is not a valid float.',
            'filterList[0].mustFilterList.filterList[12].testedValues[1]' => 'The tested value is not a valid float.',
            'filterList[0].mustFilterList.filterList[13].testedValues[0]' => 'This value is not a valid URL.',
            'filterList[0].mustFilterList.filterList[13].testedValues[1]' => 'This value is not a valid URL.',
            'filterList[0].mustFilterList.filterList[14].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[0].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[1].lowerBoundary' => 'The lower boundary is not a valid date.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[2].lowerBoundary' => 'The lower boundary is not a valid numeric (int or float).',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[3].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[5].testedValue' => 'The tested value is not a valid integer.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[6].testedValue' => 'The tested value is not a valid float.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[7].testedValue' => 'This value is not a valid URL.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[8].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[11].testedValues[1]' => 'The tested value is not a valid integer.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[12].testedValues[0]' => 'The tested value is not a valid float.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[12].testedValues[1]' => 'The tested value is not a valid float.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[13].testedValues[0]' => 'This value is not a valid URL.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[13].testedValues[1]' => 'This value is not a valid URL.',
            'filterList[0].mustFilterList.filterList[16].mustFilterList.filterList[14].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[1].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[2].lowerBoundary' => 'The lower boundary is not a valid date.',
            'filterList[3].lowerBoundary' => 'The lower boundary is not a valid numeric (int or float).',
            'filterList[4].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[6].testedValue' => 'The tested value is not a valid integer.',
            'filterList[7].testedValue' => 'The tested value is not a valid float.',
            'filterList[8].testedValue' => 'This value is not a valid URL.',
            'filterList[9].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[12].testedValues[1]' => 'The tested value is not a valid integer.',
            'filterList[13].testedValues[0]' => 'The tested value is not a valid float.',
            'filterList[13].testedValues[1]' => 'The tested value is not a valid float.',
            'filterList[14].testedValues[0]' => 'This value is not a valid URL.',
            'filterList[14].testedValues[1]' => 'This value is not a valid URL.',
            'filterList[15].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[17].positiveFilters[5]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'filterList[17].positiveFilters[11]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'filterList[17].positiveFilters.filterList[1].testedValue' => 'The tested value is not a valid integer.',
            'filterList[17].positiveFilters.filterList[2].testedValue' => 'The tested value is not a valid float.',
            'filterList[17].positiveFilters.filterList[3].testedValue' => 'This value is not a valid URL.',
            'filterList[17].positiveFilters.filterList[4].impliedMetadata.name' => 'This value should be of type string.',
            'filterList[17].positiveFilters.filterList[7].testedValues[1]' => 'The tested value is not a valid integer.',
            'filterList[17].positiveFilters.filterList[8].testedValues[0]' => 'The tested value is not a valid float.',
            'filterList[17].positiveFilters.filterList[8].testedValues[1]' => 'The tested value is not a valid float.',
            'filterList[17].positiveFilters.filterList[9].testedValues[0]' => 'This value is not a valid URL.',
            'filterList[17].positiveFilters.filterList[9].testedValues[1]' => 'This value is not a valid URL.',
            'filterList[17].positiveFilters.filterList[10].impliedMetadata.name' => 'This value should be of type string.',
        ];

        return [[$inValid, $expectedViolations]];
    }
}
