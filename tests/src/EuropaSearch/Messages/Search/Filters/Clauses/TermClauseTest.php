<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Filters\Clauses\TermClauseTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Filters\Clauses;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Search\Filters\Clauses\TermClause;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class TermClauseTest.
 *
 * Tests the validation process on Term.
 *
 * @package EC\EuropaSearch\Tests\Messages\Filters\Clauses
 */
class TermClauseTest extends AbstractEuropaSearchTest
{

    /**
     * Test the TermClause validation (success case).
     */
    public function testTermClauseValidationSuccess()
    {
        $filters = [];
        $filter = new TermClause(new StringMetadata('test_data1'));
        $filter->setTestedValue('value to use');
        $filters['test_data1'] = $filter;

        $filter = new TermClause(new FloatMetadata('test_data2'));
        $filter->setTestedValue(15.0);
        $filters['test_data2'] = $filter;

        $filter = new TermClause(new IntegerMetadata('test_data3'));
        $filter->setTestedValue(15);
        $filters['test_data3'] = $filter;

        $filter = new TermClause(new DateMetadata('test_data4'));
        $filter->setTestedValue('21-12-2017');
        $filters['test_data4'] = $filter;

        $filter = new TermClause(new URLMetadata('test_data5'));
        $filter->setTestedValue('http://www.test.com/123');
        $filters['test_data5'] = $filter;

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);
            $this->assertEmpty($violations, 'TermClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the Term validation (failure case).
     */
    public function testTermClauseValidationFailure()
    {
        $filters = [];
        $metadata = new StringMetadata(123);
        $filter = new TermClause($metadata);
        $filter->setTestedValue('value to use');
        $filters['data1'] = $filter;

        $filter = new TermClause(new FloatMetadata('test_data2'));
        $filter->setTestedValue(15);
        $filters['data2'] = $filter;

        $filter = new TermClause(new IntegerMetadata('test_data3'));
        $filter->setTestedValue(15.5);
        $filters['data3'] = $filter;

        $filter = new TermClause(new DateMetadata('test_data4'));
        $filter->setTestedValue('07-2017');
        $filters['data4'] = $filter;

        $filter = new TermClause(new URLMetadata('test_data5'));
        $filter->setTestedValue('/www.test.com123');
        $filters['data5'] = $filter;

        $filter = new TermClause(new StringMetadata('test_data6'));
        $filter->setTestedValue(['value to use']);
        $filters['data6'] = $filter;

        $validator = $this->getDefaultValidator();

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/clause_violations.yml'));
        $expected = $parsedData['expectedViolations']['TermClause'];

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'TermClause validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'TermClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
