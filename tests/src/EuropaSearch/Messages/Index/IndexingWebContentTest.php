<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Messages\Index\IndexingWebContentTest.
 */

namespace EC\EuropaSearch\Tests\Messages\Index;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Index\IndexingWebContent;
use EC\EuropaWS\Tests\AbstractEuropaSearchTest;

/**
 * Class IndexingWebContentTest
 * @package EC\EuropaSearch\Tests\Messages\Index
 */
class IndexingWebContentTest extends AbstractEuropaSearchTest
{
    /**
     * Test the IndexedDocument validation for a web content (success case).
     */
    public function testWebContentValidationSuccess()
    {

        $indexedDocument = new IndexingWebContent();
        $indexedDocument->setDocumentId('reference_indexed_document');
        $indexedDocument->setDocumentLanguage('en');
        $indexedDocument->setDocumentURI('http://test/nide/211');
        $indexedDocument->setDocumentContent('this is the content');

        $metadata = new StringMetadata('title');
        $metadata->setValues(array('The content title'));
        $indexedDocument->addMetadata($metadata);

        $metadata = new IntegerMetadata('int_sets');
        $metadata->setValues(array(1, 2, 3));
        $indexedDocument->addMetadata($metadata);

        $metadata = new DateMetadata('publishing_date');
        $metadata->setValues(array('20-12-2018'));
        $indexedDocument->addMetadata($metadata);

        $validationErrors = $this->getDefaultValidator()->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'IndexedDocument validation constraints are not well defined.');
    }

    /**
     * Test the IndexedDocument validation for a web content (failure case).
     */
    public function testWebContentValidationFailure()
    {

        $indexedDocument = new IndexingWebContent();
        $indexedDocument->setDocumentLanguage('en');

        $metadata = new StringMetadata('title');
        $metadata->setValues(array(false));
        $indexedDocument->addMetadata($metadata);

        $metadata = new IntegerMetadata('int_sets');
        $metadata->setValues(array(false));
        $indexedDocument->addMetadata($metadata);

        $metadata = new DateMetadata('publishing_date');
        $metadata->setValues(array('3000000'));
        $indexedDocument->addMetadata($metadata);

        $validationErrors = $this->getDefaultValidator()->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $expected = [
            'documentContent' => 'This value should not be blank.',
            'documentId' => 'This value should not be blank.',
            'documentURI' => 'This value should not be blank.',
            'metadata[title].values[0]' => 'This value should be of type string.',
            'metadata[publishing_date].values[0]' => 'The value is not a valid date.',
            'metadata[int_sets].values[0]' => 'This value should be of type integer.',
        ];
        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name]);
        }
    }
}
