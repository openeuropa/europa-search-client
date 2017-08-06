<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\NotIndexedMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\NotIndexedMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class NotIndexedMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class NotIndexedMetadataTest extends AbstractTest
{
    /**
     * Test the NotIndexedMetadata validation (success case).
     */
    public function testNotIndexedMetadataValidationSuccess()
    {
        $notIndexedMetadata = new NotIndexedMetadata('tested_data');
        $notIndexedMetadata->setValues(array('title is a string that is not indexed'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($notIndexedMetadata);
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

        $configuration = new ServiceConfiguration();
        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($notIndexedMetadata);
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
