<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Filters\Simple\TermsTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Filters\Simple;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Terms;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class TermsTest.
 *
 * Tests the validation process on Terms.
 *
 * @package EC\EuropaSearch\Tests\Messages\Filters\Simple
 */
class TermsTest extends AbstractEuropaSearchTest
{

    /**
     * Test the Terms validation (success case).
     */
    public function testTermsValidationSuccess()
    {

        $filters = [];
        $filter = new Terms(new StringMetadata('test_data1'));
        $filter->setTestedValues(['value to use', 'value 2']);
        $filters['test_data1'] = $filter;

        $filter = new Terms(new FloatMetadata('test_data2'));
        $filter->setTestedValues([15.0, 0.12]);
        $filters['test_data2'] = $filter;

        $filter = new Terms(new IntegerMetadata('test_data3'));
        $filter->setTestedValues([15, 235]);
        $filters['test_data3'] = $filter;

        $filter = new Terms(new DateMetadata('test_data4'));
        $filter->setTestedValues(['21-07-2017', '21-07-2017']);
        $filters['test_data4'] = $filter;

        $filter = new Terms(new URLMetadata('test_data5'));
        $filter->setTestedValues(['http://www.test.com/123']);

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);
            $this->assertEmpty($violations, 'Terms validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the Terms validation (failure case).
     */
    public function testTermsValidationFailure()
    {

        $filters = [];
        $filter = new Terms(new StringMetadata(123));
        $filter->setTestedValues(['value to use']);
        $filters['data1'] = $filter;

        $filter = new Terms(new FloatMetadata('test_data2'));
        $filter->setTestedValues([15, 0.12]);
        $filters['data2'] = $filter;

        $filter = new Terms(new IntegerMetadata('test_data3'));
        $filter->setTestedValues([2, 15.0]);
        $filters['data3'] = $filter;

        $filter = new Terms(new DateMetadata('test_data4'));
        $filter->setTestedValues(['07-2017']);
        $filters['data4'] = $filter;

        $filter = new Terms(new URLMetadata('test_data5'));
        $filter->setTestedValues(['/www.test.com123']);
        $filters['data5'] = $filter;

        $validator = $this->getDefaultValidator();

        $expected = [
            'impliedMetadata.name_data1' => 'This value should be of type string.',
            'testedValues[0]_data2' => 'The tested value is not a valid float.',
            'testedValues[1]_data3' => 'The tested value is not a valid integer.',
            'testedValues[0]_data4' => 'The tested value is not a valid date.',
            'testedValues[0]_data5' => 'This value is not a valid URL.',
        ];
        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'Terms validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'Terms validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
