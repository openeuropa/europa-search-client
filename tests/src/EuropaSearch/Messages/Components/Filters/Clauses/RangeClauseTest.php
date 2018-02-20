<?php

namespace OpenEuropa\EuropaSearch\Tests\Messages\Components\Filters\Clauses;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\Filters\Clauses\RangeClause;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class RangeClauseTest.
 *
 * Tests the validation process on RangeClause.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Messages\Components\Filters\Clauses
 */
class RangeClauseTest extends AbstractEuropaSearchTest
{

    /**
     * Test the RangeClause validation (success case).
     */
    public function testRangeClauseValidationSuccess()
    {
        $filters = [];
        $rangeFilter = new RangeClause(new IntegerMetadata('test_data1'));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $filters['test_data1'] = $rangeFilter;

        $rangeFilter = new RangeClause(new FloatMetadata('test_data2'));
        $rangeFilter->setLowerBoundaryExcluded(1.11);
        $rangeFilter->setUpperBoundaryExcluded(5);
        $filters['test_data2'] = $rangeFilter;

        $rangeFilter = new RangeClause(new IntegerMetadata('test_data3'));
        $rangeFilter->setLowerBoundaryExcluded(235);
        $filters['test_data3'] = $rangeFilter;

        $rangeFilter = new RangeClause(new DateMetadata('test_data4'));
        $rangeFilter->setLowerBoundaryExcluded('30-07-2018');
        $filters['test_data4'] = $rangeFilter;

        $rangeFilter = new RangeClause(new DateMetadata('test_data5'));
        $rangeFilter->setUpperBoundaryIncluded('30-07-2018');
        $filters['test_data5'] = $rangeFilter;

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertEmpty($violations, 'RangeClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the RangeClause validation (failure case).
     */
    public function testRangeClauseValidationFailure()
    {
        $filters = [];
        $rangeFilter = new RangeClause(new IntegerMetadata(1234));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $filters['data1'] = $rangeFilter;

        $rangeFilter = new RangeClause(new FloatMetadata('test_data2'));
        $rangeFilter->setLowerBoundaryExcluded('blabla');
        $rangeFilter->setUpperBoundaryExcluded(5.0);
        $filters['data2'] = $rangeFilter;

        $rangeFilter = new RangeClause(new IntegerMetadata('test_data3'));
        $rangeFilter->setLowerBoundaryExcluded('235');
        $filters['data3'] = $rangeFilter;

        $rangeFilter = new RangeClause(new IntegerMetadata('test_data4'));
        $rangeFilter->setLowerBoundaryExcluded(235);
        $rangeFilter->setUpperBoundaryExcluded('5');
        $filters['data4'] = $rangeFilter;

        $rangeFilter = new RangeClause(new DateMetadata('test_data5'));
        $rangeFilter->setLowerBoundaryExcluded('32-33-2018');
        $filters['data5'] = $rangeFilter;

        $rangeFilter = new RangeClause(new DateMetadata('test_data5'));
        $rangeFilter->setUpperBoundaryIncluded('07-2018');
        $filters['data6'] = $rangeFilter;

        $validator = $this->getDefaultValidator();

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/clause_violations.yml'));
        $expected = $parsedData['expectedViolations']['RangeClause'];

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'RangeClause validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'RangeClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
