<?php

namespace EC\EuropaSearch\Tests\Messages\Components\Filters\Clauses;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Components\Filters\Clauses\TermsClause;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class TermsClauseTest.
 *
 * Tests the validation process on Terms.
 *
 * @package EC\EuropaSearch\Tests\Messages\Components\Filters\Clauses
 */
class TermsClauseTest extends AbstractEuropaSearchTest
{

    /**
     * Test the Terms validation (success case).
     */
    public function testTermsClauseValidationSuccess()
    {
        $filters = [];
        $filter = new TermsClause(new StringMetadata('test_data1'));
        $filter->setTestedValues(['value to use', 'value 2']);
        $filters['test_data1'] = $filter;

        $filter = new TermsClause(new FloatMetadata('test_data2'));
        $filter->setTestedValues([15.0, 0.12]);
        $filters['test_data2'] = $filter;

        $filter = new TermsClause(new IntegerMetadata('test_data3'));
        $filter->setTestedValues([15, 235]);
        $filters['test_data3'] = $filter;

        $filter = new TermsClause(new DateMetadata('test_data4'));
        $filter->setTestedValues(['21-07-2017', '21-07-2017']);
        $filters['test_data4'] = $filter;

        $filter = new TermsClause(new URLMetadata('test_data5'));
        $filter->setTestedValues(['http://www.test.com/123']);

        $validator = $this->getDefaultValidator();

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);
            $this->assertEmpty($violations, 'TermsClause validation constraints are not well defined for: '.$testedData);
        }
    }

    /**
     * Test the Terms validation (failure case).
     */
    public function testTermsValidationFailure()
    {
        $filters = [];
        $filter = new TermsClause(new StringMetadata(123));
        $filter->setTestedValues(['value to use']);
        $filters['data1'] = $filter;

        $filter = new TermsClause(new FloatMetadata('test_data2'));
        $filter->setTestedValues([15, 0.12]);
        $filters['data2'] = $filter;

        $filter = new TermsClause(new IntegerMetadata('test_data3'));
        $filter->setTestedValues([2, 15.0]);
        $filters['data3'] = $filter;

        $filter = new TermsClause(new DateMetadata('test_data4'));
        $filter->setTestedValues(['07-2017']);
        $filters['data4'] = $filter;

        $filter = new TermsClause(new URLMetadata('test_data5'));
        $filter->setTestedValues(['/www.test.com123']);
        $filters['data5'] = $filter;

        $validator = $this->getDefaultValidator();

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/clause_violations.yml'));
        $expected = $parsedData['expectedViolations']['TermsClause'];

        foreach ($filters as $testedData => $filter) {
            $validationErrors = $validator->validate($filter);
            $violations = $this->getViolations($validationErrors);

            $this->assertNotEmpty($violations, 'TermsClause validation failed because it raises no error for: '.$testedData);

            foreach ($violations as $name => $violation) {
                $name .= '_'.$testedData;
                $this->assertEquals($violation, $expected[$name], 'TermsClause validation constraints are not well defined for: '.$testedData);
            }
        }
    }
}
