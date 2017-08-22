<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\IntegerMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

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

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type integer.',
            'values[1]' => 'This value should be of type integer.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IntegerMetadata validation constraints are not well defined.');
        }
    }
}
