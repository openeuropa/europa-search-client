<?php

namespace OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\FloatMetadata;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FloatMetadataTest.
 *
 * Tests the validation process on FloatMetadata.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata
 */
class FloatMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the FloatMetadata validation (success case).
     */
    public function testFloatMetadataValidationSuccess()
    {
        $floatMetadata = new FloatMetadata('tested_float');
        $floatMetadata->setValues([1.2, 20.003, 20.0]);

        $validationErrors = $this->getDefaultValidator()->validate($floatMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'FloatMetadata validation constraints are not well defined.');
    }

    /**
     * Test the FloatMetadata validation (failure case).
     */
    public function testFloatMetadataValidationFailure()
    {
        $floatMetadata = new FloatMetadata(null);
        $floatMetadata->setValues(['0.2', 0]);

        $validationErrors = $this->getDefaultValidator()->validate($floatMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['FloatMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FloatMetadata validation constraints are not well defined.');
        }
    }
}
