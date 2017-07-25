<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\DocumentMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class DocumentMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class DocumentMetadataTest extends AbstractTest
{
    /**
     * Test the DocumentMetadata validation (success case).
     */
    public function testDocumentMetadataValidationSuccess()
    {
        $documentMetadata = new DocumentMetadata('title', 'This is the content type', 'string', 1);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($documentMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'DocumentMetadata validation constraints are not well defined.');
    }

    /**
     * Test the DocumentMetadata date validation (success case).
     */
    public function testDocumentMetadataDateValidationSuccess()
    {
        $documentMetadata = new DocumentMetadata('tested_date', '30-12-2018', 'date', 1);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($documentMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'DocumentMetadata validation constraints are not well defined.');
    }

    /**
     * Test the DocumentMetadata validation (failure case).
     */
    public function testDocumentMetadataValidationFailure()
    {

        $documentMetadata = new DocumentMetadata(null, null, 123, '1');
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($documentMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'name' => 'This value should not be blank.',
            'type' => 'The value you selected is not a valid choice.',
            'value' => 'This value should not be null.',
            'boost' => 'This value should be of type int.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'DocumentMetadata validation constraints are not well defined.');
        }
    }

    /**
     * Test the DocumentMetadata date validation (failure case).
     */
    public function testDocumentMetadataDateValidationFailure()
    {

        $documentMetadata = new DocumentMetadata('date', '33-33-2105', 'date', 1);
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($documentMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'value' => 'The value is not a valid date as the type is "date".',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'DocumentMetadata validation constraints are not well defined.');
        }
    }
}
