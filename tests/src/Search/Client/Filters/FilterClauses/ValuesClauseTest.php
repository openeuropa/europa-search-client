<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses\ValuesClauseTest.
 */

namespace EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\StringMetadata;
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
        $clause = new ValuesClause(new StringMetadata('test_data1'));
        $clause->setTestedValues(array('value to use', 'value 2'));
        $clauses['test_data1'] = $clause;

        $clause = new ValuesClause(new FloatMetadata('test_data2'));
        $clause->setTestedValues(array(15.0, 0.12));
        $clauses['test_data2'] = $clause;

        $clause = new ValuesClause(new IntegerMetadata('test_data3'));
        $clause->setTestedValues(array(15, 235));
        $clauses['test_data3'] = $clause;

        $clause = new ValuesClause(new DateMetadata('test_data4'));
        $clause->setTestedValues(array('21-07-2017', '21-07-2017'));
        $clauses['test_data4'] = $clause;

        $clause = new ValuesClause(new URLMetadata('test_data5'));
        $clause->setTestedValues(array('http://www.test.com/123'));

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
        $clause = new ValuesClause(new StringMetadata(123));
        $clause->setTestedValues(array('value to use'));
        $clauses['data1'] = $clause;

        $clause = new ValuesClause(new FloatMetadata('test_data2'));
        $clause->setTestedValues(array(15, 0.12));
        $clauses['data2'] = $clause;

        $clause = new ValuesClause(new IntegerMetadata('test_data3'));
        $clause->setTestedValues(array(2, 15.0));
        $clauses['data3'] = $clause;

        $clause = new ValuesClause(new DateMetadata('test_data4'));
        $clause->setTestedValues(array('07-2017'));
        $clauses['data4'] = $clause;

        $clause = new ValuesClause(new URLMetadata('test_data5'));
        $clause->setTestedValues(array('/www.test.com123'));
        $clauses['data5'] = $clause;

        $clause = new ValuesClause(new NotIndexedMetadata('test_data6'));
        $clause->setTestedValues(array('value to use'));
        $clauses['data6'] = $clause;

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');

        $expected = [
            'impliedMetadata.name_data1' => 'This value should be of type string.',
            'testedValue[0]_data2' => 'The tested value is not a valid float.',
            'testedValue[1]_data3' => 'The tested value is not a valid int.',
            'testedValue[0]_data4' => 'The tested value is not a valid date.',
            'testedValue[0]_data5' => 'This value is not a valid URL.',
            'impliedMetadata_data6' => 'The metadata is not supported for this kind of clause.',
        ];
        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'ValuesClause validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'ValuesClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
