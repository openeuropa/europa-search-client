<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\DateMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaWS\Tests\AbstractEuropaSearchTest;

/**
 * Class DateMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class DateMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the DateMetadata date validation (success case).
     */
    public function testDateMetadataValidationSuccess()
    {
        $dateDocumentMetadata = new DateMetadata('tested_date');
        $dateDocumentMetadata->setValues(array('30-12-2018'));

        $validationErrors = $this->getDefaultValidator()->validate($dateDocumentMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'DateMetadata validation constraints are not well defined.');
    }

    /**
     * Test the DateMetadata date validation (failure case).
     */
    public function testDateMetadataValidationFailure()
    {
        $dateDocumentMetadata = new DateMetadata('tested_date');
        $dateDocumentMetadata->setValues(array('33-33-2105'));

        $validationErrors = $this->getDefaultValidator()->validate($dateDocumentMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'values[0]' => 'The value is not a valid date.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'DateMetadata validation constraints are not well defined.');
        }
    }
}
