<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Search\Filters\Queries\BooleanQueryTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Search\Filters\Queries;

use EC\EuropaSearch\Messages\Search\Filters\Queries\BooleanQuery;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BooleanQueryTest.
 *
 * Tests the validation process on BooleanQuery.
 *
 * @package EC\EuropaSearch\Tests\Messages\Search\Filters\Queries
 */
class BooleanQueryTest extends AbstractEuropaSearchTest
{

    /**
     * Test the BooleanQuery validation (success case).
     *
     * @param array $booleanQuery
     *   Array with a valid BooleanQuery objects.
     *
     * @dataProvider validBooleanQueryProvider
     */
    public function testBooleanQueryValidationSuccess($booleanQuery)
    {

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($booleanQuery);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'BooleanQuery validation constraints are not well defined.');
    }

    /**
     * Test the BooleanQuery validation (failure case).
     *
     * @param array $booleanQuery
     *   Array with an invalid BooleanQuery objects.
     * @param array $expectedViolations
     *   Array the expected violation messages indexed by invalid
     *   property path.
     *
     * @dataProvider invalidBooleanQueryProvider
     */
    public function testBooleanQueryValidationFailure($booleanQuery, $expectedViolations)
    {

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($booleanQuery);
        $violations = $this->getViolations($validationErrors);

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expectedViolations[$name], 'BooleanQuery validation constraints are not well defined');
        }
    }

    /**
     * Provides valid BooleanQuery for testing.
     *
     * @return  array
     *   Array with valid BooleanQuery objects.
     */
    public static function validBooleanQueryProvider()
    {

        $booleanProvider = new BooleanQueryDataProvider();

        $returned = [[]];

        $returned[0][] = $booleanProvider->getValidSimpleBooleanQuery();
        $returned[1][] = $booleanProvider->getValidNestedBooleanQuery();

        return $returned;
    }

    /**
     * Provides invalid BooleanQuery object for testing.
     *
     * @return array
     *   Array with invalid BooleanQuery objects.
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function invalidBooleanQueryProvider()
    {

        $booleanProvider = new BooleanQueryDataProvider();

        $returned = [[]];


        $fileContent = file_get_contents(__DIR__.'/fixtures/booleanquery_violations.yml');
        $parsedData = Yaml::parse($fileContent);
        $expectedViolations = $parsedData['expectedViolations'];

        $returned[0][] = $booleanProvider->getMustInvalidSimpleBooleanQuery();
        $returned[0][] = $expectedViolations[0];

        $returned[1][] = $booleanProvider->getMustNotInvalidSimpleBooleanQuery();
        $returned[1][] = $expectedViolations[1];

        $returned[2][] = $booleanProvider->getShouldInvalidSimpleBooleanQuery();
        $returned[2][] = $expectedViolations[2];

        $returned[3][] = $booleanProvider->getMustInvalidNestedBooleanQuery();
        $returned[3][] = $expectedViolations[3];

        $returned[4][] = $booleanProvider->getMustNotInvalidNestedBooleanQuery();
        $returned[4][] = $expectedViolations[4];

        $returned[5][] = $booleanProvider->getShouldInValidNestedBooleanQuery();
        $returned[5][] = $expectedViolations[5];

        $returned[6][] = new BooleanQuery();
        $returned[6][] = $expectedViolations[6];

        return $returned;
    }
}
