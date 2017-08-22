<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\FloatMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class FloatMetadataTest.
 *
 * Tests the validation process on FloatMetadata.
 *
 * @package EC\EuropaSearch\Tests\Common
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

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type float.',
            'values[1]' => 'This value should be of type float.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FloatMetadata validation constraints are not well defined.');
        }
    }
}
