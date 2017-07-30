<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\URLMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class URLMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class URLMetadataTest extends AbstractTest
{
    /**
     * Test the URLMetadata validation (success case).
     */
    public function testURLMetadataValidationSuccess()
    {
        $uRLMetadata = new URLMetadataTest('tested_data', array('http://metadata.com'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($uRLMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'URLMetadata validation constraints are not well defined.');
    }

    /**
     * Test the URLMetadata validation (failure case).
     */
    public function testURLMetadataValidationFailure()
    {

        $uRLMetadata = new URLMetadata(null, array(true, 0));
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($uRLMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value is not a valid URL.',
            'values[1]' => 'This value is not a valid URL.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'URLMetadata validation constraints are not well defined.');
        }
    }
}
