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
        $rangeClause = new RangeClause(new IntegerMetadata('test_data1'));
        $rangeClause->setLowerBoundaryIncluded(1);
        $rangeClause->setUpperBoundaryIncluded(5);
        $clauses['test_data1'] = $rangeClause;

        $rangeClause = new RangeClause(new FloatMetadata('test_data2'));
        $rangeClause->setLowerBoundaryExcluded(1.11);
        $rangeClause->setUpperBoundaryExcluded(5);
        $clauses['test_data2'] = $rangeClause;

        $rangeClause = new RangeClause(new IntegerMetadata('test_data3'));
        $rangeClause->setLowerBoundaryExcluded(235);
        $clauses['test_data3'] = $rangeClause;

        $rangeClause = new RangeClause(new DateMetadata('test_data4'));
        $rangeClause->setLowerBoundaryExcluded('30-07-2018');
        $clauses['test_data4'] = $rangeClause;

        $rangeClause = new RangeClause(new DateMetadata('test_data5'));
        $rangeClause->setUpperBoundaryIncluded('30-07-2018');
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
        $rangeClause = new RangeClause(new IntegerMetadata(1234));
        $rangeClause->setLowerBoundaryIncluded(1);
        $rangeClause->setUpperBoundaryIncluded(5);
        $clauses['data1'] = $rangeClause;

        $rangeClause = new RangeClause(new FloatMetadata('test_data2'));
        $rangeClause->setLowerBoundaryExcluded('blabla');
        $rangeClause->setUpperBoundaryExcluded(5.0);
        $clauses['data2'] = $rangeClause;

        $rangeClause = new RangeClause(new IntegerMetadata('test_data3'));
        $rangeClause->setLowerBoundaryExcluded('235');
        $clauses['data3'] = $rangeClause;

        $rangeClause = new RangeClause(new IntegerMetadata('test_data4'));
        $rangeClause->setLowerBoundaryExcluded(235);
        $rangeClause->setUpperBoundaryExcluded('5');
        $clauses['data4'] = $rangeClause;

        $rangeClause = new RangeClause(new DateMetadata('test_data5'));
        $rangeClause->setLowerBoundaryExcluded('32-33-2018');
        $clauses['data5'] = $rangeClause;

        $rangeClause = new RangeClause(new DateMetadata('test_data5'));
        $rangeClause->setUpperBoundaryIncluded('07-2018');
        $clauses['data6'] = $rangeClause;

        $rangeClause = new RangeClause(new NotIndexedMetadata('test_data7'));
        $rangeClause->setUpperBoundaryIncluded('30-07-2018');
        $clauses['data7'] = $rangeClause;

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new SearchServiceContainer($configuration))->get('validator');


        $expected = [
            'impliedMetadata.name_data1' => 'This value should be of type string.',
            'lowerBoundary_data2' => 'The lower boundary is not a valid numeric (int or float).',
            'lowerBoundary_data3' => 'The lower boundary is not a valid int.',
            'upperBoundary_data4' => 'The upper boundary is not a valid int.',
            'lowerBoundary_data5' => 'The lower boundary is not a valid date.',
            'upperBoundary_data6' => 'The upper boundary is not a valid date.',
            'impliedMetadata_data7' => 'The metadata is not supported for this kind of clause.',
        ];
        foreach ($clauses as $testedData => $clause) {
            $validationErrors = $validator->validate($clause);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'RangeClause validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'RangeClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
