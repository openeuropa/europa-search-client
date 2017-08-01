<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses\RangeClauseTest.
 */

namespace EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses;

use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Search\Client\Filters\FilterClauses\RangeClause;
use EC\EuropaSearch\Search\SearchServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class RangeClauseTest.
 *
 * @package EC\EuropaSearch\Tests\Search\Client\Filters\FilterClauses
 */
class RangeClauseTest extends AbstractTest
{

    /**
     * Test the RangeClause validation (success case).
     */
    public function testRangeClauseValidationSuccess()
    {
        $clauses = array();
        $rangeClause = new RangeClause('test_data1', 'integer');
        $rangeClause->setLowerBoundary(1, true);
        $rangeClause->setUpperBoundary(5, true);
        $clauses['test_data1'] = $rangeClause;

        $rangeClause = new RangeClause('test_data2', FloatMetadata::TYPE);
        $rangeClause->setLowerBoundary(1.11);
        $rangeClause->setUpperBoundary(5);
        $clauses['test_data2'] = $rangeClause;

        $rangeClause = new RangeClause('test_data3', IntegerMetadata::TYPE);
        $rangeClause->setLowerBoundary(235);
        $clauses['test_data3'] = $rangeClause;

        $rangeClause = new RangeClause('test_data4', DateMetadata::TYPE);
        $rangeClause->setLowerBoundary('30-07-2018');
        $clauses['test_data4'] = $rangeClause;

        $rangeClause = new RangeClause('test_data5', DateMetadata::TYPE);
        $rangeClause->setUpperBoundary('30-07-2018', true);
        $clauses['test_data5'] = $rangeClause;

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');

        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);
            $this->assertEmpty($violations, 'FieldExistsClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the RangeClause validation (failure case).
     */
    public function testRangeClauseValidationFailure()
    {
        $clauses = array();
        $rangeClause = new RangeClause(1234, 'integer');
        $rangeClause->setLowerBoundary(1, true);
        $rangeClause->setUpperBoundary(5, true);
        $clauses['data1'] = $rangeClause;

        $rangeClause = new RangeClause('test_data2', FloatMetadata::TYPE);
        $rangeClause->setLowerBoundary('blabla');
        $rangeClause->setUpperBoundary(5.0);
        $clauses['data2'] = $rangeClause;

        $rangeClause = new RangeClause('test_data3', IntegerMetadata::TYPE);
        $rangeClause->setLowerBoundary('235');
        $clauses['data3'] = $rangeClause;

        $rangeClause = new RangeClause('test_data4', IntegerMetadata::TYPE);
        $rangeClause->setLowerBoundary(235);
        $rangeClause->setUpperBoundary('5');
        $clauses['data4'] = $rangeClause;

        $rangeClause = new RangeClause('test_data5', DateMetadata::TYPE);
        $rangeClause->setLowerBoundary('32-33-2018');
        $clauses['data5'] = $rangeClause;

        $rangeClause = new RangeClause('test_data5', DateMetadata::TYPE);
        $rangeClause->setUpperBoundary('07-2018', true);
        $clauses['data6'] = $rangeClause;

        $rangeClause = new RangeClause('test_data7', NotIndexedMetadata::TYPE);
        $rangeClause->setUpperBoundary('30-07-2018', true);
        $clauses['data7'] = $rangeClause;

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');


        $expected = [
            'impliedMetadataName_data1' => 'This value should be of type string.',
            'lowerBoundary_data2' => 'The lower boundary is not a valid numeric (int or float).',
            'lowerBoundary_data3' => 'The lower boundary is not a valid int.',
            'upperBoundary_data4' => 'The upper boundary is not a valid int.',
            'lowerBoundary_data5' => 'The lower boundary is not a valid date.',
            'upperBoundary_data6' => 'The upper boundary is not a valid date.',
            'impliedMetadataType_data7' => 'This value should not be equal to "not_indexed".',
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
