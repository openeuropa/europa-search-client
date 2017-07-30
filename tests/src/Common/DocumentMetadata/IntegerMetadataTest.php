<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\IntegerMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class IntegerMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class IntegerMetadataTest extends AbstractTest
{
    /**
     * Test the IntegerMetadata validation (success case).
     */
    public function testIntegerMetadataValidationSuccess()
    {
        $integerMetadata = new IntegerMetadataTest('tested_integer', array(1, 2, 300000000000000));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($integerMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'IntegerMetadata validation constraints are not well defined.');
    }

    /**
     * Test the IntegerMetadata validation (failure case).
     */
    public function testIntegerMetadataValidationFailure()
    {

        $integerMetadata = new IntegerMetadata(null, array('0.2', false));
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($integerMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type int.',
            'values[1]' => 'This value should be of type int.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IntegerMetadata validation constraints are not well defined.');
        }
    }
}
