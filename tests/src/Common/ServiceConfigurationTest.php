<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\ServiceConfigurationTest.
 */
namespace EC\EuropaSearch\Tests\Common;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class ServiceConfigurationTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class ServiceConfigurationTest extends AbstractTest
{
    /**
     * Test the ServiceConfiguration validation (success case).
     */
    public function testConfigurationValidationSuccess()
    {


        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($configuration);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'ServiceConfiguration validation constraints are not well defined.');
    }

    /**
     * Test the ServiceConfiguration validation (failure case).
     */
    public function testConfigurationValidationFailure()
    {


        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($configuration);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'serviceRoot' => 'This value is not a valid URL.',
            'database' => 'This value should be of type string.',
            'apiKey' => 'This value should be of type string.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'ServiceConfiguration validation constraints are not well defined.');
        }
    }
}
