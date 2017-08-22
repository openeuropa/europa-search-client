<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\URLMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class URLMetadataTest.
 *
 * Tests the validation process on URLMetadata.
 *
 * @package EC\EuropaSearch\Tests\Common
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

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type string.',
            'values[1]' => 'This value is not a valid URL.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'URLMetadata validation constraints are not well defined.');
        }
    }
}
