<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses\FieldExistsClauseTest.

 */

namespace EC\EuropaSearchTests\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Search\Client\Filters\FilterClauses\FieldExistsClause;
use EC\EuropaSearch\Search\SearchServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class FieldExistsClauseTest.
 *
 * @package EC\EuropaSearch\Search\Client\Filters\FilterClauses
 */
class FieldExistsClauseTest extends AbstractTest
{

    /**
     * Test the FieldExistsClause validation (success case).
     */
    public function testFieldExistsClauseValidationSuccess()
    {
        $clauses = array();

        $clauses['test_data1'] = new FieldExistsClause(new StringMetadata('test_data1'));
        $clauses['test_data2'] = new FieldExistsClause(new BooleanMetadata('test_data2'));
        $clauses['test_data3'] = new FieldExistsClause(new IntegerMetadata('test_data3'));
        $clauses['test_data4'] = new FieldExistsClause(new DateMetadata('test_data4'));
        $clauses['test_data5'] = new FieldExistsClause(new FloatMetadata('test_data5'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');

        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);

            $this->assertEmpty($violations, 'FieldExistsClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the FieldExistsClause validation (failure case).
     */
    public function testFieldExistsClauseValidationFailure()
    {
        $clauses = array();
        $clauses['data1'] = new FieldExistsClause(new BooleanMetadata(1234));
        $clauses['data2'] = new FieldExistsClause(new NotIndexedMetadata('test_data2'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');


        $expected = [
            'impliedMetadata.name_data1' => 'This value should be of type string.',
            'impliedMetadata_data2' => 'The metadata is not supported for this kind of clause.',
        ];
        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'FieldExistsClause validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'FieldExistsClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
