<?php

namespace EC\EuropaSearch\Tests\Messages\Index;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Index\IndexFileMessage;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class IndexFileMessageTest.
 *
 * Tests the validation process on IndexFileMessage.
 *
 * @package EC\EuropaSearch\Tests\Messages\Index
 */
class IndexFileMessageTest extends AbstractEuropaSearchTest
{

    /**
     * Test the IndexWebContentMessage validation (success case).
     */
    public function testIndexWebContentMessageValidationSuccess()
    {
        $indexedFile = new IndexFileMessage();
        $indexedFile->setDocumentId('reference_indexed_document');
        $indexedFile->setDocumentLanguage('en');
        $indexedFile->setDocumentURI('http://test/nid/211');
        $indexedFile->setDocumentFile('/direction/to/file');

        $metadata = new StringMetadata('title');
        $metadata->setValues(['The content title']);
        $indexedFile->addMetadata($metadata);

        $metadata = new IntegerMetadata('int_sets');
        $metadata->setValues([1, 2, 3]);
        $indexedFile->addMetadata($metadata);

        $metadata = new DateMetadata('publishing_date');
        $metadata->setValues(['20-12-2018']);
        $indexedFile->addMetadata($metadata);

        $validationErrors = $this->getDefaultValidator()->validate($indexedFile);
        $violations = $this->getViolations($validationErrors);

        $this->assertEmpty($violations, 'IndexWebContentMessage validation constraints are not well defined.');
    }

    /**
     * Test the IndexWebContentMessage validation (failure case).
     */
    public function testIndexWebContentMessageValidationFailure()
    {
        $indexedDocument = new IndexFileMessage();
        $indexedDocument->setDocumentLanguage('en');

        $metadata = new StringMetadata('title');
        $metadata->setValues([false]);
        $indexedDocument->addMetadata($metadata);

        $metadata = new IntegerMetadata('int_sets');
        $metadata->setValues([false]);
        $indexedDocument->addMetadata($metadata);

        $metadata = new DateMetadata('publishing_date');
        $metadata->setValues(['3000000']);
        $indexedDocument->addMetadata($metadata);

        $validationErrors = $this->getDefaultValidator()->validate($indexedDocument);
        $violations = $this->getViolations($validationErrors);

        $parsedData = Yaml::parse(file_get_contents(__DIR__.'/fixtures/index_violations.yml'));
        $expected = $parsedData['expectedViolations']['IndexFileMessage'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IndexWebContentMessage validation constraints are not well defined for: '.$name);
        }
    }
}
