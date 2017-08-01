<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses\ValueClauseTest.
 */

namespace EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Search\Client\Filters\FilterClauses\ValueClause;
use EC\EuropaSearch\Search\SearchServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class ValueClauseTest.
 *
 * @package EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses
 */
class ValueClauseTest extends AbstractTest
{

    /**
     * Test the ValueClause validation (success case).
     */
    public function testValueClauseValidationSuccess()
    {
        $clauses = array();
        $clauses['test_data1'] = new ValueClause('test_data1', 'string', 'value to use');
        $clauses['test_data2'] = new ValueClause('test_data2', 'float', 15.0);
        $clauses['test_data3'] = new ValueClause('test_data3', IntegerMetadata::TYPE, 15);
        $clauses['test_data4'] = new ValueClause('test_data4', DateMetadata::TYPE, '21-07-2017');
        $clauses['test_data5'] = new ValueClause('test_data5', URLMetadata::TYPE, 'http://www.test.com/123');

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');

        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);
            $this->assertEmpty($violations, 'ValueClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the ValueClause validation (failure case).
     */
    public function testValueClauseValidationFailure()
    {
        $clauses = array();
        $clauses['data1'] = new ValueClause(123, 'string', 'value to use');
        $clauses['data2'] = new ValueClause('test_data2', 'float', 15);
        $clauses['data3'] = new ValueClause('test_data3', IntegerMetadata::TYPE, 15.0);
        $clauses['data4'] = new ValueClause('test_data4', DateMetadata::TYPE, '07-2017');
        $clauses['data5'] = new ValueClause('test_data5', URLMetadata::TYPE, '/www.test.com123');
        $clauses['data6'] = new ValueClause('test_data6', 'string', array('value to use'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');


        $expected = [
            'impliedMetadataName_data1' => 'This value should be of type string.',
            'testedValue_data2' => 'The tested value is not a valid float.',
            'testedValue_data3' => 'The tested value is not a valid int.',
            'testedValue_data4' => 'The tested value is not a valid date.',
            'testedValue_data5' => 'This value is not a valid URL.',
            'testedValue_data6' => 'This value should be of type scalar.',
        ];
        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);
            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'ValueClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
