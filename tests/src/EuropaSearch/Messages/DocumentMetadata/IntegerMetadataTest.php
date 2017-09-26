<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\IntegerMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class IntegerMetadataTest.
 *
 * Tests the validation process on IntegerMetadata.
 *
 * @package EC\EuropaSearch\Tests\Common
 */
class IntegerMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the IntegerMetadata validation (success case).
     */
    public function testIntegerMetadataValidationSuccess()
    {
        $integerMetadata = new IntegerMetadata('tested_integer');
        $integerMetadata->setValues([1, 2, 300000000000000]);

        $validationErrors = $this->getDefaultValidator()->validate($integerMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'IntegerMetadata validation constraints are not well defined.');
    }

    /**
     * Test the IntegerMetadata validation (failure case).
     */
    public function testIntegerMetadataValidationFailure()
    {
        $integerMetadata = new IntegerMetadata(null);
        $integerMetadata->setValues(['0.2', false]);

        $validationErrors = $this->getDefaultValidator()->validate($integerMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['IntegerMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IntegerMetadata validation constraints are not well defined.');
        }
    }
}
