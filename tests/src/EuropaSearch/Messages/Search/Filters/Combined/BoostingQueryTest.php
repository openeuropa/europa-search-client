<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Combined\BoostingQueryTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\BoostingQuery;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class BoostingQueryTest.
 *
 * Tests the validation process on BoostingQuery.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Combined
 */
class BoostingQueryTest extends AbstractEuropaSearchTest
{

    /**
     * Test the BoostingQuery validation (success case).
     *
     * @dataProvider validBoostingQueryProvider
     *
     * @param array $boostingQuery
     *   The BoostingQuery object to test.
     */
    public function testBoostingQueryValidationSuccess($boostingQuery)
    {

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($boostingQuery);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'BoostingQuery validation constraints are not well defined.');
    }


    /**
     * Test the BoostingQuery validation (failure case).
     *
     * @param array $boostingQuery
     *   The BoostingQuery object to test.
     * @param array $expectedViolations
     *   Array the expected violation messages indexed by invalid
     *   property path.
     *
     * @dataProvider invalidBoostingQueryProvider
     */
    public function testBoostingQueryValidationFailure($boostingQuery, $expectedViolations)
    {

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($boostingQuery);
        $violations = $this->getViolations($validationErrors);

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expectedViolations[$name], 'BoostingQuery validation constraints are not well defined, see: '.$name);
        }
    }

    /**
     * Provides valid BoostingQuery for testing.
     *
     * @return  array
     *   Array with valid BoostingQuery objects.
     */
    public static function validBoostingQueryProvider()
    {

        $boostingProvider = new BoostingQueryDataProvider();
        $boostingQuery = $boostingProvider->getValidBoostingQuery();

        return [[$boostingQuery]];
    }

    /**
     * Provides invalid BoostingQuery object for testing.
     *
     * @return array
     *   Array with invalid BoostingQuery objects.
     */
    public static function invalidBoostingQueryProvider()
    {

        $boostingProvider = new BoostingQueryDataProvider();

        $returned = [[]];

        $returned[0][] = $boostingProvider->getPositiveInValidBoostingQuery();
        $expectedViolations = [
            'positiveFilters[5]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'positiveFilters[11]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'positiveFilters.filterList[1].testedValue' => 'The tested value is not a valid integer.',
            'positiveFilters.filterList[2].testedValue' => 'The tested value is not a valid float.',
            'positiveFilters.filterList[3].testedValue' => 'This value is not a valid URL.',
            'positiveFilters.filterList[4].impliedMetadata.name' => 'This value should be of type string.',
            'positiveFilters.filterList[7].testedValues[1]' => 'The tested value is not a valid integer.',
            'positiveFilters.filterList[8].testedValues[0]' => 'The tested value is not a valid float.',
            'positiveFilters.filterList[8].testedValues[1]' => 'The tested value is not a valid float.',
            'positiveFilters.filterList[9].testedValues[0]' => 'This value is not a valid URL.',
            'positiveFilters.filterList[9].testedValues[1]' => 'This value is not a valid URL.',
            'positiveFilters.filterList[10].impliedMetadata.name' => 'This value should be of type string.',
        ];
        $returned[0][] = $expectedViolations;

        $returned[1][] = $boostingProvider->getNegativeInValidBoostingQuery();
        $expectedViolations = [
            'negativeFilters[5]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'negativeFilters[11]' => 'The Metadata implied in the filter is not supported. Only text and numerical ones are valid.',
            'negativeFilters.filterList[1].testedValue' => 'The tested value is not a valid integer.',
            'negativeFilters.filterList[2].testedValue' => 'The tested value is not a valid float.',
            'negativeFilters.filterList[3].testedValue' => 'This value is not a valid URL.',
            'negativeFilters.filterList[4].impliedMetadata.name' => 'This value should be of type string.',
            'negativeFilters.filterList[7].testedValues[1]' => 'The tested value is not a valid integer.',
            'negativeFilters.filterList[8].testedValues[0]' => 'The tested value is not a valid float.',
            'negativeFilters.filterList[8].testedValues[1]' => 'The tested value is not a valid float.',
            'negativeFilters.filterList[9].testedValues[0]' => 'This value is not a valid URL.',
            'negativeFilters.filterList[9].testedValues[1]' => 'This value is not a valid URL.',
            'negativeFilters.filterList[10].impliedMetadata.name' => 'This value should be of type string.',
        ];
        $returned[1][] = $expectedViolations;

        $returned[2][] = new BoostingQuery();
        $expectedViolations = [
            'positiveFilters' => 'At least one of the filter list must filled.',
        ];
        $returned[2][] = $expectedViolations;


        return $returned;
    }
}
