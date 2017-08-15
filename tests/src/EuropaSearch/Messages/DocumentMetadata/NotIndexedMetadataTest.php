<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\NotIndexedMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Messages\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaWS\Tests\AbstractEuropaSearchTest;

/**
 * Class NotIndexedMetadataTest.
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
        $notIndexedMetadata->setValues(array('title is a string that is not indexed'));

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
        $notIndexedMetadata->setValues(array(true, 0));

        $validationErrors = $this->getDefaultValidator()->validate($notIndexedMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type string.',
            'values[1]' => 'This value should be of type string.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'NotIndexedMetadata validation constraints are not well defined.');
        }
    }
}
