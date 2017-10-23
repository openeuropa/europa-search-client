<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\DateMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class DateMetadataTest.
 *
 * Tests the validation process on DateMetadata.
 *
 * @package EC\EuropaSearch\Tests\Common
 */
class DateMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the DateMetadata date validation (success case).
     */
    public function testDateMetadataValidationSuccess()
    {
        // Test with actual date.
        $dateDocumentMetadata = new DateMetadata('tested_date');
        $dateDocumentMetadata->setValues(['30-12-2018']);

        $validationErrors = $this->getDefaultValidator()->validate($dateDocumentMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'DateMetadata validation constraints are not well defined with date.');

        // Test with timestamp.
        $dateDocumentMetadata->setTimestampValues([136526644]);

        $validationErrors = $this->getDefaultValidator()->validate($dateDocumentMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'DateMetadata validation constraints are not well defined with a timestamp.');
    }

    /**
     * Test the DateMetadata date validation (failure case).
     */
    public function testDateMetadataValidationFailure()
    {
        $dateDocumentMetadata = new DateMetadata('tested_date');
        $dateDocumentMetadata->setValues(['33-33-2105']);

        $validationErrors = $this->getDefaultValidator()->validate($dateDocumentMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['DateMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'DateMetadata validation constraints are not well defined.');
        }
    }
}
