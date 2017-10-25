<?php

namespace EC\EuropaSearch\Tests\Messages\Components\Filters\Queries;

use EC\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BooleanQueryTest.
 *
 * Tests the validation process on BooleanQuery.
 *
 * @package EC\EuropaSearch\Tests\Messages\Components\Filters\Queries
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

        $returned = [
            [$booleanProvider->getValidSimpleBooleanQuery()],
            [$booleanProvider->getValidNestedBooleanQuery()],
        ];

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

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/booleanquery_violations.yml'));
        $expectedViolations = $parsedData['expectedViolations'];

        $returned = [
            [
                $booleanProvider->getMustInvalidSimpleBooleanQuery(),
                $expectedViolations['getMustInvalidSimpleBooleanQuery'],
            ],
            [
                $booleanProvider->getMustNotInvalidSimpleBooleanQuery(),
                $expectedViolations['getMustNotInvalidSimpleBooleanQuery'],
            ],
            [
                $booleanProvider->getShouldInvalidSimpleBooleanQuery(),
                $expectedViolations['getShouldInvalidSimpleBooleanQuery'],
            ],
            [
                $booleanProvider->getMustInvalidNestedBooleanQuery(),
                $expectedViolations['getMustInvalidNestedBooleanQuery'],
            ],
            [
                $booleanProvider->getMustNotInvalidNestedBooleanQuery(),
                $expectedViolations['getMustNotInvalidNestedBooleanQuery'],
            ],
            [
                $booleanProvider->getShouldInValidNestedBooleanQuery(),
                $expectedViolations['getShouldInValidNestedBooleanQuery'],
            ],
            [
                new BooleanQuery(),
                $expectedViolations['BooleanQuery'],
            ],
        ];

        return $returned;
    }
}
