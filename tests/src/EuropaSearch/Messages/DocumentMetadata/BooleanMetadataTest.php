<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\BooleanMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BooleanMetadataTest.
 *
 * Tests the validation process on BooleanMetadata.
 *
 * @package EC\EuropaSearch\Tests\Common
 */
class BooleanMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the BooleanMetadata validation (success case).
     */
    public function testBooleanMetadataValidationSuccess()
    {
        $booleanMetadata = new BooleanMetadata('tested_boolean');
        $booleanMetadata->setValues([true, false]);

        $validationErrors = $this->getDefaultValidator()->validate($booleanMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'BooleanMetadata validation constraints are not well defined.');
    }

    /**
     * Test the BooleanMetadata validation (failure case).
     */
    public function testBooleanMetadataValidationFailure()
    {
        $booleanMetadata = new BooleanMetadata(null);
        $booleanMetadata->setValues([true, 0]);

        $validationErrors = $this->getDefaultValidator()->validate($booleanMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['BooleanMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'BooleanMetadata validation constraints are not well defined.');
        }
    }
}
