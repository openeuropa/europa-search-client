<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\NotIndexedMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class NotIndexedMetadataTest.
 *
 * Tests the validation process on NotIndexedMetadata.
 *
 * @package EC\EuropaSearch\Tests\Common
 */
class NotIndexedMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the NotIndexedMetadata validation (success case).
     */
    public function testNotIndexedMetadataValidationSuccess()
    {
        $notIndexedMetadata = new NotIndexedMetadata('tested_data');
        $notIndexedMetadata->setValues(['title is a string that is not indexed']);

        $validationErrors = $this->getDefaultValidator()->validate($notIndexedMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'NotIndexedMetadata validation constraints are not well defined.');
    }

    /**
     * Test the NotIndexedMetadata validation (failure case).
     */
    public function testNotIndexedMetadataValidationFailure()
    {
        $notIndexedMetadata = new NotIndexedMetadata(null);
        $notIndexedMetadata->setValues([true, 0]);

        $validationErrors = $this->getDefaultValidator()->validate($notIndexedMetadata);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/documentmetadata_violations.yml'));
        $expected = $parsedData['expectedViolations']['NotIndexedMetadata'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'NotIndexedMetadata validation constraints are not well defined.');
        }
    }
}
