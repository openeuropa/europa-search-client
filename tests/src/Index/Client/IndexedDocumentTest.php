<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Index\Client\IndexedDocumentTest.
 */

namespace EC\EuropaSearch\Tests\Index\Client;

use EC\EuropaSearch\Index\Client\IndexedDocument;
use EC\EuropaSearch\Index\IndexServiceContainer;
use EC\EuropaSearch\Tests\AbstractTest;

/**
 * Class IndexedDocumentTest.
 * @package EC\EuropaSearch\Tests\Index\Client
 */
class IndexedDocumentTest extends AbstractTest
{
    /**
     * Test the IndexedDocument validation for a web content (success case).
     */
    public function testWebContentValidationSuccess()
    {

        $indexedDocument = new IndexedDocument();
        $indexedDocument->setDocumentId('reference_indexed_document');
        $indexedDocument->setDocumentLanguage('en');
        $indexedDocument->setDocumentType(IndexedDocument::WEB_CONTENT);
        $indexedDocument->setDocumentURI('http://test/nide/211');
        $indexedDocument->setDocumentContent('this is the content');

        $indexedDocument->addStringMetadata('title', array('The content title'));
        $indexedDocument->addDateMetadata('publishing_date', array('20-12-2018'));
        $indexedDocument->addIntMetadata('int_sets', array(1, 2, 3));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'IndexedDocument validation constraints are not well defined.');
    }

    /**
     * Test the IndexedDocument validation for a web content (failure case).
     */
    public function testWebContentValidationFailure()
    {
        $indexedDocument = new IndexedDocument();
        $indexedDocument->setDocumentLanguage('en');
        $indexedDocument->setDocumentType(IndexedDocument::WEB_CONTENT);

        $indexedDocument->addStringMetadata('title', array(false));
        $indexedDocument->addDateMetadata('publishing_date', array('3000000'));
        $indexedDocument->addIntMetadata('int_sets', array(false));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'documentContent' => 'The documentContent should not be empty as the indexed document is a web content.',
            'documentId' => 'This value should not be blank.',
            'documentURI' => 'This value should not be blank.',
            'metadata[title].values[0]' => 'This value should be of type string.',
            'metadata[publishing_date].values[0]' => 'The value is not a valid date.',
            'metadata[int_sets].values[0]' => 'This value should be of type int.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name]);
        }
    }
    /**
     * Test the IndexedDocument validation for a file (success case).
     */
    public function testBinaryValidationSuccess()
    {

        $indexedDocument = new IndexedDocument();
        $indexedDocument->setDocumentId('reference_indexed_document');
        $indexedDocument->setDocumentLanguage('en');
        $indexedDocument->setDocumentType(IndexedDocument::BINARY);
        $indexedDocument->setDocumentURI('http://test/nide/211');

        $indexedDocument->addStringMetadata('title', array('The content title'));
        $indexedDocument->addDateMetadata('publishing_date', array('20-12-2018'));
        $indexedDocument->addIntMetadata('int_sets', array(1, 2, 3));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'IndexedDocument validation constraints are not well defined.');
    }

    /**
     * Test the IndexedDocument validation for a file (failure case).
     */
    public function testBinaryValidationFailure()
    {
        $indexedDocument = new IndexedDocument();
        $indexedDocument->setDocumentLanguage('en');
        $indexedDocument->setDocumentType(IndexedDocument::BINARY);
        $indexedDocument->setDocumentContent('this is the content');

        $indexedDocument->addStringMetadata('title', array(false));
        $indexedDocument->addDateMetadata('publishing_date', array('3000000'));
        $indexedDocument->addIntMetadata('int_sets', array(false));

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'documentContent' => 'The documentContent should be empty as the indexed document is a file.',
            'documentId' => 'This value should not be blank.',
            'documentURI' => 'This value should not be blank.',
            'metadata[title].values[0]' => 'This value should be of type string.',
            'metadata[publishing_date].values[0]' => 'The value is not a valid date.',
            'metadata[int_sets].values[0]' => 'This value should be of type int.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IndexedDocument validation constraints are not well defined.');
        }
    }
}
