<?php

namespace OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\URLMetadata;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class URLMetadataTest.
 *
 * Tests the validation process on URLMetadata.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata
 */
class URLMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the URLMetadata validation (success case).
     */
    public function testURLMetadataValidationSuccess()
    {
        $uRLMetadata = new URLMetadata('tested_data');
        $uRLMetadata->setValues(['http://metadata.com']);

        $validationErrors = $this->getDefaultValidator()->validate($uRLMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'URLMetadata validation constraints are not well defined.');
    }

    /**
     * Test the URLMetadata validation (failure case).
     */
    public function testURLMetadataValidationFailure()
    {
        $uRLMetadata = new URLMetadata(null);
        $uRLMetadata->setValues([true, '/blabla/test']);

        $validationErrors = $this->getDefaultValidator()->validate($uRLMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['URLMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'URLMetadata validation constraints are not well defined.');
        }
    }
}
