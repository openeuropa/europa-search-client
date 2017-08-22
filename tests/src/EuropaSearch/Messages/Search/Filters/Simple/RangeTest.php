<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Filters\Simple\RangeTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Filters\Simple;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Range;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class RangeTest.
 *
 * Tests the validation process on Range.
 *
 * @package EC\EuropaSearch\Tests\Messages\Filters\Simple
 */
class RangeTest extends AbstractEuropaSearchTest
{

    /**
     * Test the Range validation (success case).
     */
    public function testRangeValidationSuccess()
    {

        $filters = [];
        $rangeFilter = new Range(new IntegerMetadata('test_data1'));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $filters['test_data1'] = $rangeFilter;

        $rangeFilter = new Range(new FloatMetadata('test_data2'));
        $rangeFilter->setLowerBoundaryExcluded(1.11);
        $rangeFilter->setUpperBoundaryExcluded(5);
        $filters['test_data2'] = $rangeFilter;

        $rangeFilter = new Range(new IntegerMetadata('test_data3'));
        $rangeFilter->setLowerBoundaryExcluded(235);
        $filters['test_data3'] = $rangeFilter;

        $rangeFilter = new Range(new DateMetadata('test_data4'));
        $rangeFilter->setLowerBoundaryExcluded('30-07-2018');
        $filters['test_data4'] = $rangeFilter;

        $rangeFilter = new Range(new DateMetadata('test_data5'));
        $rangeFilter->setUpperBoundaryIncluded('30-07-2018');
        $filters['test_data5'] = $rangeFilter;

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertEmpty($violations, 'Range validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the Range validation (failure case).
     */
    public function testRangeValidationFailure()
    {

        $filters = [];
        $rangeFilter = new Range(new IntegerMetadata(1234));
        $rangeFilter->setLowerBoundaryIncluded(1);
        $rangeFilter->setUpperBoundaryIncluded(5);
        $filters['data1'] = $rangeFilter;

        $rangeFilter = new Range(new FloatMetadata('test_data2'));
        $rangeFilter->setLowerBoundaryExcluded('blabla');
        $rangeFilter->setUpperBoundaryExcluded(5.0);
        $filters['data2'] = $rangeFilter;

        $rangeFilter = new Range(new IntegerMetadata('test_data3'));
        $rangeFilter->setLowerBoundaryExcluded('235');
        $filters['data3'] = $rangeFilter;

        $rangeFilter = new Range(new IntegerMetadata('test_data4'));
        $rangeFilter->setLowerBoundaryExcluded(235);
        $rangeFilter->setUpperBoundaryExcluded('5');
        $filters['data4'] = $rangeFilter;

        $rangeFilter = new Range(new DateMetadata('test_data5'));
        $rangeFilter->setLowerBoundaryExcluded('32-33-2018');
        $filters['data5'] = $rangeFilter;

        $rangeFilter = new Range(new DateMetadata('test_data5'));
        $rangeFilter->setUpperBoundaryIncluded('07-2018');
        $filters['data6'] = $rangeFilter;

        $validator = $this->getDefaultValidator();


        $expected = [
            'impliedMetadata.name_data1' => 'This value should be of type string.',
            'lowerBoundary_data2' => 'The lower boundary is not a valid numeric (int or float).',
            'lowerBoundary_data3' => 'The lower boundary is not a valid integer.',
            'upperBoundary_data4' => 'The upper boundary is not a valid integer.',
            'lowerBoundary_data5' => 'The lower boundary is not a valid date.',
            'upperBoundary_data6' => 'The upper boundary is not a valid date.',
        ];
        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'Range validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'Range validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
