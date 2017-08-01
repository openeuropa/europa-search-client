<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses\FieldExistsClauseTest.

 */

namespace EC\EuropaSearchTests\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
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
        $clauses['test_data1'] = new FieldExistsClause('test_data1', 'string');
        $clauses['test_data2'] = new FieldExistsClause('test_data2', 'boolean');
        $clauses['test_data3'] = new FieldExistsClause('test_data3', IntegerMetadata::TYPE);
        $clauses['test_data4'] = new FieldExistsClause('test_data4', DateMetadata::TYPE);
        $clauses['test_data5'] = new FieldExistsClause('test_data5', FloatMetadata::TYPE);

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
        $clauses['data1'] = new FieldExistsClause(1234, 'string');
        $clauses['data2'] = new FieldExistsClause('test_data2', 'bool');
        $clauses['data3'] = new FieldExistsClause('test_data3', NotIndexedMetadata::TYPE);
        $clauses['data4'] = new FieldExistsClause('test_data4', 'not_indexed');

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');


        $expected = [
            'impliedMetadataName_data1' => 'This value should be of type string.',
            'impliedMetadataType_data2' => 'The value you selected is not a valid choice.',
            'impliedMetadataType_data3' => 'This value should not be equal to "not_indexed".',
            'impliedMetadataType_data4' => 'This value should not be equal to "not_indexed".',
        ];
        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);
            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'FieldExistsClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
