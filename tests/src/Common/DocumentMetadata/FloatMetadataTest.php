<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\FloatMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class FloatMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class FloatMetadataTest extends AbstractTest
{
    /**
     * Test the FloatMetadata validation (success case).
     */
    public function testFloatMetadataValidationSuccess()
    {
        $floatMetadata = new FloatMetadataTest('tested_float', array(1.2, 20.003, 20.0));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($floatMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'FloatMetadata validation constraints are not well defined.');
    }

    /**
     * Test the FloatMetadata validation (failure case).
     */
    public function testFloatMetadataValidationFailure()
    {

        $floatMetadata = new FloatMetadata(null, array('0.2', 0));
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($floatMetadata);
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
