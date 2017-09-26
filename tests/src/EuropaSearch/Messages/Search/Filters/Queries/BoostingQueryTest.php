<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Queries\BoostingQueryTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Queries\BoostingQuery;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BoostingQueryTest.
 *
 * Tests the validation process on BoostingQuery.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Queries
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

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/boostingquery_violations.yml'));
        $expectedViolations = $parsedData['expectedViolations'];

        $returned = [
            [
                $boostingProvider->getPositiveInValidBoostingQuery(),
                $expectedViolations['getPositiveInValidBoostingQuery'],
            ],
            [
                $boostingProvider->getNegativeInValidBoostingQuery(),
                $expectedViolations['getNegativeInValidBoostingQuery'],
            ],
            [
                new BoostingQuery(),
                $expectedViolations['BoostingQuery'],
            ],
        ];

        return $returned;
    }
}
