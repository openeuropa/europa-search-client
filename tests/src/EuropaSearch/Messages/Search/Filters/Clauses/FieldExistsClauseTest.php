<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Filters\Clauses\FieldExistClauseTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Filters\Clauses;

use EC\EuropaSearch\Messages\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Search\Filters\Clauses\FieldExistsClause;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class FieldExistClauseTest.
 *
 * Tests the validation process on FieldExist.
 *
 * @package EC\EuropaSearch\Tests\Messages\Filters\Clauses
 */
class FieldExistClauseTest extends AbstractEuropaSearchTest
{

    /**
     * Test the FieldExistClause validation (success case).
     */
    public function testFieldExistsClauseValidationSuccess()
    {

        $filters = [];

        $filters['data1'] = new FieldExistsClause(new StringMetadata('test_data1'));
        $filters['data2'] = new FieldExistsClause(new BooleanMetadata('test_data2'));
        $filters['data3'] = new FieldExistsClause(new IntegerMetadata('test_data3'));
        $filters['data4'] = new FieldExistsClause(new DateMetadata('test_data4'));
        $filters['data5'] = new FieldExistsClause(new FloatMetadata('test_data5'));

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertEmpty($violations, 'FieldExistClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the FieldExistClause validation (failure case).
     */
    public function testFieldExistClauseValidationFailure()
    {

        $filter = new FieldExistsClause(new StringMetadata(1234));

        $validator = $this->getDefaultValidator();

        $validationErrors = $validator->validate($filter);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'impliedMetadata.name' => 'This value should be of type string.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FieldExistClause validation constraints are not well defined');
        }
    }
}