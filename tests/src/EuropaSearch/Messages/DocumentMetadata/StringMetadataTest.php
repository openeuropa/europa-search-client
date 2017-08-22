<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\StringMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

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

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type string.',
            'values[1]' => 'This value should be of type string.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'StringMetadata validation constraints are not well defined.');
        }
    }
}
