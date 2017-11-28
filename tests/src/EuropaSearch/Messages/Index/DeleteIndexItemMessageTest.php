<?php

namespace EC\EuropaSearch\Tests\Messages\Index;

use EC\EuropaSearch\Messages\Index\DeleteIndexItemMessage;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class DeleteIndexItemMessageTest.
 *
 * Tests the validation process on DeleteIndexItemMessage.
 *
 * @package EC\EuropaSearch\Tests\Messages\Index
 */
class DeleteIndexItemMessageTest extends AbstractEuropaSearchTest
{

    /**
     * Test the DeleteIndexItemMessage validation (success case).
     */
    public function testIndexedItemDeletionValidationSuccess()
    {

        $indexedDocument = new DeleteIndexItemMessage();
        $indexedDocument->setDocumentId('reference_indexed_document');

        $validationErrors = $this->getDefaultValidator()->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'DeleteIndexItemMessage validation constraints are not well defined.');
    }

    /**
     * Test the IndexWebContentMessage validation (failure case).
     */
    public function testIndexedItemDeletionValidationFailure()
    {
        $indexedDocument = new DeleteIndexItemMessage();

        $validationErrors = $this->getDefaultValidator()->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/index_violations.yml'));
        $expected = $parsedData['expectedViolations']['DeleteIndexItemMessage'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'DeleteIndexItemMessage validation constraints are not well defined for: '.$name);
        }
    }
}
