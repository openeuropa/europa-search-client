<?php

namespace OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\BooleanMetadata;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class BooleanMetadataTest.
 *
 * Tests the validation process on BooleanMetadata.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Messages\Components\DocumentMetadata
 */
class BooleanMetadataTest extends AbstractEuropaSearchTest
{

    /**
     * Test the BooleanMetadata validation (success case).
     */
    public function testBooleanMetadataValidationSuccess()
    {
        // Tests with real booleans (true or false).
        $booleanMetadata = new BooleanMetadata('tested_boolean');
        $booleanMetadata->setValues([true, false]);

        $validationErrors = $this->getDefaultValidator()->validate($booleanMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'BooleanMetadata validation constraints are not well defined with an array of real booleans.');

        // Tests with boolean equivalent booleans (0, empty).
        $booleanMetadata->setRawValues(['', 0, 1, true]);

        $validationErrors = $this->getDefaultValidator()->validate($booleanMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'BooleanMetadata validation constraints are not well defined with an array of raw values.');
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
