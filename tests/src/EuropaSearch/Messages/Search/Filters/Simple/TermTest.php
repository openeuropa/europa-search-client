<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Filters\Simple\TermTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Filters\Simple;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Term;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class TermTest.
 *
 * Tests the validation process on Term.
 *
 * @package EC\EuropaSearch\Tests\Messages\Filters\Simple
 */
class TermTest extends AbstractEuropaSearchTest
{

    /**
     * Test the Term validation (success case).
     */
    public function testTermValidationSuccess()
    {

        $filters = [];
        $filter = new Term(new StringMetadata('test_data1'));
        $filter->setTestedValue('value to use');
        $filters['test_data1'] = $filter;

        $filter = new Term(new FloatMetadata('test_data2'));
        $filter->setTestedValue(15.0);
        $filters['test_data2'] = $filter;

        $filter = new Term(new IntegerMetadata('test_data3'));
        $filter->setTestedValue(15);
        $filters['test_data3'] = $filter;

        $filter = new Term(new DateMetadata('test_data4'));
        $filter->setTestedValue('21-12-2017');
        $filters['test_data4'] = $filter;

        $filter = new Term(new URLMetadata('test_data5'));
        $filter->setTestedValue('http://www.test.com/123');
        $filters['test_data5'] = $filter;

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);
            $this->assertEmpty($violations, 'Term validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the Term validation (failure case).
     */
    public function testTermValidationFailure()
    {

        $filters = [];
        $metadata = new StringMetadata(123);
        $filter = new Term($metadata);
        $filter->setTestedValue('value to use');
        $filters['data1'] = $filter;

        $filter = new Term(new FloatMetadata('test_data2'));
        $filter->setTestedValue(15);
        $filters['data2'] = $filter;

        $filter = new Term(new IntegerMetadata('test_data3'));
        $filter->setTestedValue(15.0);
        $filters['data3'] = $filter;

        $filter = new Term(new DateMetadata('test_data4'));
        $filter->setTestedValue('07-2017');
        $filters['data4'] = $filter;

        $filter = new Term(new URLMetadata('test_data5'));
        $filter->setTestedValue('/www.test.com123');
        $filters['data5'] = $filter;

        $filter = new Term(new StringMetadata('test_data6'));
        $filter->setTestedValue(['value to use']);
        $filters['data6'] = $filter;

        $validator = $this->getDefaultValidator();

        $expected = [
            'impliedMetadata.name_data1' => 'This value should be of type string.',
            'testedValue_data2' => 'The tested value is not a valid float.',
            'testedValue_data3' => 'The tested value is not a valid integer.',
            'testedValue_data4' => 'The tested value is not a valid date.',
            'testedValue_data5' => 'This value is not a valid URL.',
            'testedValue_data6' => 'This value should be of type scalar.',
        ];
        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'Term validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'Term validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
