<?php

namespace OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\FullTextMetadata;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class FullTextMetadataTest.
 *
 * Tests the validation process on FullTextMetadata.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata
 */
class FullTextMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the FullTextMetadata validation (success case).
     */
    public function testFullTextMetadataValidationSuccess()
    {
        $fullTextMetadata = new FullTextMetadata('tested_data');
        $fullTextMetadata->setValues(['full text searchable data is a string']);

        $validationErrors = $this->getDefaultValidator()->validate($fullTextMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'FullTextMetadata validation constraints are not well defined.');
    }

    /**
     * Test the FullTextMetadata validation (failure case).
     */
    public function testFullTextMetadataValidationFailure()
    {
        $fullTextMetadata = new FullTextMetadata(null);
        $fullTextMetadata->setValues([true, 0]);

        $validationErrors = $this->getDefaultValidator()->validate($fullTextMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['FullTextMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FullTextMetadata validation constraints are not well defined.');
        }
    }
}
