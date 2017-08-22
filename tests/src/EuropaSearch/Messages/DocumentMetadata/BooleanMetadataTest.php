<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\BooleanMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

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

        $expected = [
            'name' => 'This value should not be blank.',
            'values[1]' => 'This value should be of type bool.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'BooleanMetadata validation constraints are not well defined.');
        }
    }
}
