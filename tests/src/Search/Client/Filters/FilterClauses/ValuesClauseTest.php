<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses\ValuesClauseTest.
 */

namespace EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Search\Client\Filters\FilterClauses\ValuesClause;
use EC\EuropaSearch\Search\SearchServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class ValuesClauseTest.
 *
 * @package EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses
 */
class ValuesClauseTest extends AbstractTest
{

    /**
     * Test the ValuesClause validation (success case).
     */
    public function testValuesClauseValidationSuccess()
    {
        $clauses = array();
        $clauses['test_data1'] = new ValuesClause('test_data1', 'string', array('value to use', 'value 2'));
        $clauses['test_data2'] = new ValuesClause('test_data2', 'float', array(15.0, 0.12));
        $clauses['test_data3'] = new ValuesClause('test_data3', IntegerMetadata::TYPE, array(15, 235));
        $clauses['test_data4'] = new ValuesClause('test_data4', DateMetadata::TYPE, array('21-07-2017', '21-07-2017'));
        $clauses['test_data5'] = new ValuesClause('test_data5', URLMetadata::TYPE, array('http://www.test.com/123'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');

        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);
            $this->assertEmpty($violations, 'ValuesClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the ValuesClause validation (failure case).
     */
    public function testValuesClauseValidationFailure()
    {
        $clauses = array();
        $clauses['data1'] = new ValuesClause(123, 'string', array('value to use'));
        $clauses['data2'] = new ValuesClause('test_data2', 'float', array(15, 0.12));
        $clauses['data3'] = new ValuesClause('test_data3', IntegerMetadata::TYPE, array(2, 15.0));
        $clauses['data4'] = new ValuesClause('test_data4', DateMetadata::TYPE, array('07-2017'));
        $clauses['data5'] = new ValuesClause('test_data5', URLMetadata::TYPE, array('/www.test.com123', 'http://www.test.com/123'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');


        $expected = [
            'impliedMetadataName_data1' => 'This value should be of type string.',
            'testedValue[0]_data2' => 'The tested value is not a valid float.',
            'testedValue[1]_data3' => 'The tested value is not a valid int.',
            'testedValue[0]_data4' => 'The tested value is not a valid date.',
            'testedValue[0]_data5' => 'This value is not a valid URL.',
        ];
        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);
            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'ValuesClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
