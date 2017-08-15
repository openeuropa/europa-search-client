<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\FullTextMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\FullTextMetadata;
use EC\EuropaWS\Tests\AbstractEuropaSearchTest;

/**
 * Class FullTextMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class FullTextMetadataTest extends AbstractEuropaSearchTest
{
    /**
     * Test the FullTextMetadata validation (success case).
     */
    public function testFullTextMetadataValidationSuccess()
    {
        $fullTextMetadata = new FullTextMetadata('tested_data');
        $fullTextMetadata->setValues(array('full text searchable data is a string'));

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
        $fullTextMetadata->setValues(array(true, 0));

        $validationErrors = $this->getDefaultValidator()->validate($fullTextMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type string.',
            'values[1]' => 'This value should be of type string.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FullTextMetadata validation constraints are not well defined.');
        }
    }
}
