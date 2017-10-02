<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\StringMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class StringMetadataTest.
 *
 * Tests the validation process on StringMetadata.
 *
 * @package EC\EuropaSearch\Tests\Common
 */
class StringMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the StringMetadata validation (success case).
     */
    public function testStringMetadataValidationSuccess()
    {
        $stringMetadata = new StringMetadata('tested_string');
        $stringMetadata->setValues(['title is a string']);

        $validationErrors = $this->getDefaultValidator()->validate($stringMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'StringMetadata validation constraints are not well defined.');
    }

    /**
     * Test the StringMetadata validation (failure case).
     */
    public function testStringMetadataValidationFailure()
    {
        $stringMetadata = new StringMetadata(null);
        $stringMetadata->setValues([true, 0]);

        $validationErrors = $this->getDefaultValidator()->validate($stringMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['StringMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'StringMetadata validation constraints are not well defined.');
        }
    }
}
