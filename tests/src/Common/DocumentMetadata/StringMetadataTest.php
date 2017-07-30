<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\StringMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class StringMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class StringMetadataTest extends AbstractTest
{
    /**
     * Test the StringMetadata validation (success case).
     */
    public function testStringMetadataValidationSuccess()
    {
        $stringMetadata = new StringMetadataTest('tested_string', array('title is a string'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($stringMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'StringMetadata validation constraints are not well defined.');
    }

    /**
     * Test the StringMetadata validation (failure case).
     */
    public function testStringMetadataValidationFailure()
    {

        $stringMetadata = new StringMetadata(null, array(true, 0));
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($stringMetadata);
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
