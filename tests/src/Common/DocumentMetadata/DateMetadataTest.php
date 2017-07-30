<?php
/**
 * @file
 * Contains EC\EuropaSearch\Tests\Common\DateMetadataTest.
 */

namespace EC\EuropaSearch\Tests\Common\DocumentMetadata;

use EC\EuropaSearch\Common\ServiceConfiguration;
use EC\EuropaSearch\Common\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class DateMetadataTest.
 * @package EC\EuropaSearch\Tests\Common
 */
class DateMetadataTest extends AbstractTest
{

    /**
     * Test the DateMetadata date validation (success case).
     */
    public function testDateMetadataValidationSuccess()
    {
        $dateDocumentMetadata = new DateMetadata('tested_date', array('30-12-2018'));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($dateDocumentMetadata);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'DateMetadata validation constraints are not well defined.');
    }

    /**
     * Test the DateMetadata date validation (failure case).
     */
    public function testDateMetadataValidationFailure()
    {

        $dateDocumentMetadata = new DateMetadata('tested_date', array('33-33-2105'));
        $configuration = new ServiceConfiguration();

        $configuration->setServiceRoot('htp://false/test');
        $configuration->setApiKey(123);
        $configuration->setDatabase(2992);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($dateDocumentMetadata);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'values[0]' => 'The value is not a valid date.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'DateMetadata validation constraints are not well defined.');
        }
    }
}
