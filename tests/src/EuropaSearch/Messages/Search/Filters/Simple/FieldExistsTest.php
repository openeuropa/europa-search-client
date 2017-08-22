<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Filters\Simple\FieldExistTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Filters\Simple;

use EC\EuropaSearch\Messages\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Search\Filters\Simple\FieldExists;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class FieldExistTest.
 *
 * Tests the validation process on FieldExist.
 *
 * @package EC\EuropaSearch\Tests\Messages\Filters\Simple
 */
class FieldExistsTest extends AbstractEuropaSearchTest
{

    /**
     * Test the FieldExist validation (success case).
     */
    public function testFieldExistsValidationSuccess()
    {

        $filters = [];

        $filters['data1'] = new FieldExists(new StringMetadata('test_data1'));
        $filters['data2'] = new FieldExists(new BooleanMetadata('test_data2'));
        $filters['data3'] = new FieldExists(new IntegerMetadata('test_data3'));
        $filters['data4'] = new FieldExists(new DateMetadata('test_data4'));
        $filters['data5'] = new FieldExists(new FloatMetadata('test_data5'));

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertEmpty($violations, 'FieldExist validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the FieldExist validation (failure case).
     */
    public function testFieldExistSValidationFailure()
    {

        $filter = new FieldExists(new StringMetadata(1234));

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($filter);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'impliedMetadata.name' => 'This value should be of type string.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FieldExist validation constraints are not well defined');
        }
    }
}
