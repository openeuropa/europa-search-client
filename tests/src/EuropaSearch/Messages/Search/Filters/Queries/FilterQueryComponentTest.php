<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Queries\AggregatedFilterTest;
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Queries\FilterQueryComponent;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use EC\EuropaSearch\Tests\Messages\Search\Filters\Clauses\FilterClauseDataProvider;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FilterQueryComponentTest.
 *
 * Tests the validation process on FilterQueryComponent.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Queries
 */
class FilterQueryComponentTest extends AbstractEuropaSearchTest
{

    /**
     * Test the FilterQueryComponent validation (success case).
     *
     * @param array $aggregatedFilter
     *   Array with a valid FilterQueryComponent objects.
     *
     * @dataProvider validFilterQueryComponentProvider
     */
    public function testFilterQueryComponentValidationSuccess($aggregatedFilter)
    {

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($aggregatedFilter);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'FilterQueryComponent validation constraints are not well defined.');
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
     * @dataProvider invalidFilterQueryComponentProvider
     */
    public function testFilterQueryComponentValidationFailure($aggregatedFilter, $expectedViolations)
    {
        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($aggregatedFilter);
        $violations = $this->getViolations($validationErrors);

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expectedViolations[$name], 'AggregatedFilter validation constraints are not well defined');
        }
    }

    /**
     * Provides valid FilterQueryComponent for testing.
     *
     * @return  array
     *   Array with a valid FilterQueryComponent objects.
     */
    public static function validFilterQueryComponentProvider()
    {
        $filterProvider = new FilterClauseDataProvider();
        $boostingProvider = new BoostingQueryDataProvider();
        $booleanProvider = new BooleanQueryDataProvider();

        $valid = new FilterQueryComponent('must');

        $nestedBooleanQuery = $booleanProvider->getValidNestedBooleanQuery();
        $valid->addFilterQuery($nestedBooleanQuery);

        $validFilters = $filterProvider->getValidFilters();
        foreach ($validFilters as $validFilter) {
            $valid->addFilterClause($validFilter);
        }

        $boostingQuery = $boostingProvider->getValidBoostingQuery();
        $valid->addFilterQuery($boostingQuery);

        return [[$valid]];
    }

    /**
     * Provides invalid AggregatedFilter object for testing.
     *
     * @return array
     *   Array with invalid AggregatedFilter objects.
     */
    public static function invalidFilterQueryComponentProvider()
    {
        $inValid = new FilterQueryComponent('must');

        $booleanProvider = new BooleanQueryDataProvider();
        $nestedBooleanQuery = $booleanProvider->getMustInvalidNestedBooleanQuery();
        $inValid->addFilterQuery($nestedBooleanQuery);

        $filterProvider = new FilterClauseDataProvider();
        $validFilters = $filterProvider->getInValidFilters();
        foreach ($validFilters as $validFilter) {
            $inValid->addFilterClause($validFilter);
        }

        $boostingProvider = new BoostingQueryDataProvider();
        $boostingQuery = $boostingProvider->getPositiveInValidBoostingQuery();
        $inValid->addFilterQuery($boostingQuery);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/filterquerycomponent_violations.yml'));
        $expectedViolations = $parsedData['expectedViolations']['filterquerycomponent'];

        return [[$inValid, $expectedViolations]];
    }
}
