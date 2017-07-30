<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\BooleanMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\BooleanMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class BooleanMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class BooleanMetadataTest extends AbstractTest
{
    /**
     * Test the BooleanMetadata validation (success case).
     */
    public function testBooleanMetadataValidationSuccess()
    {
        $booleanMetadata = new BooleanMetadataTest('tested_boolean', array(true, false));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($booleanMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'BooleanMetadata validation constraints are not well defined.');
    }

    /**
     * Test the BooleanMetadata validation (failure case).
     */
    public function testBooleanMetadataValidationFailure()
    {

        $booleanMetadata = new BooleanMetadata(null, array(true, 0));
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($booleanMetadata);
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
