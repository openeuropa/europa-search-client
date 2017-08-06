<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\FullTextMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class FullTextMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class FullTextMetadataTest extends AbstractTest
{
    /**
     * Test the FullTextMetadata validation (success case).
     */
    public function testFullTextMetadataValidationSuccess()
    {
        $fullTextMetadata = new FullTextMetadata('tested_data');
        $fullTextMetadata->setValues(array('full text searchable data is a string'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($fullTextMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'FullTextMetadata validation constraints are not well defined.');
    }

    /**
     * Test the FullTextMetadata validation (failure case).
     */
    public function testFullTextMetadataValidationFailure()
    {
        $fullTextMetadata = new FullTextMetadata(null);
        $fullTextMetadata->setValues(array(true, 0));

        $configuration = new ServiceConfiguration();
        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($fullTextMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'name' => 'This value should not be blank.',
            'values[0]' => 'This value should be of type string.',
            'values[1]' => 'This value should be of type string.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'FullTextMetadata validation constraints are not well defined.');
        }
    }
}
