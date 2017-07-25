<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Index\Client\IndexedDocumentTest.
 */

namespace EC\EuropaSearch\Tests\Index\Client;

use EC\EuropaSearch\Common\DocumentMetadata;
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

        $metaDataList = array(
            'title' => new DocumentMetadata('title', 'The content title', 'string'),
            'other_metadata' => new DocumentMetadata('other_metadata', 'Other metadata', 'string'),
        );
        $indexedDocument->setMetadata($metaDataList);

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

        $metaDataList = array(
            'title' => 'error_to_test',
            'other_metadata' => new DocumentMetadata('other_metadata', null, 'string', 'error_to_test'),
        );
        $indexedDocument->setMetadata($metaDataList);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'documentContent' => 'The documentContent should not be empty as the indexed document is a web content.',
            'documentId' => 'This value should not be blank.',
            'documentURI' => 'This value should not be blank.',
            'metadata[title]' => 'This value should be of type \EC\EuropaSearch\Common\DocumentMetadata.',
            'metadata[other_metadata].value' => 'This value should not be null.',
            'metadata[other_metadata].boost' => 'This value should be of type int.',
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

        $metaDataList = array(
            'title' => new DocumentMetadata('title', 'The file title', 'string'),
            'other_metadata' => new DocumentMetadata('other_metadata', 'Other metadata', 'string'),
        );
        $indexedDocument->setMetadata($metaDataList);

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

        $metaDataList = array(
            'title' => 'error_to_test',
            'other_metadata' => new DocumentMetadata('other_metadata', null, 'string', 'error_to_test'),
        );
        $indexedDocument->setMetadata($metaDataList);

        $configuration = $this->getServiceConfigurationDummy();
        $validator = (new IndexServiceContainer($configuration))->get('validator');
        $validationErrors = $validator->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'documentContent' => 'The documentContent should be empty as the indexed document is a file.',
            'documentId' => 'This value should not be blank.',
            'documentURI' => 'This value should not be blank.',
            'metadata[title]' => 'This value should be of type \EC\EuropaSearch\Common\DocumentMetadata.',
            'metadata[other_metadata].value' => 'This value should not be null.',
            'metadata[other_metadata].boost' => 'This value should be of type int.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IndexedDocument validation constraints are not well defined.');
        }
    }
}
