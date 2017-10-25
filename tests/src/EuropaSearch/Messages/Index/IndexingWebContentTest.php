<?php

namespace EC\EuropaSearch\Tests\Messages\Index;

use EC\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Index\IndexingWebContent;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class IndexingWebContentTest.
 *
 * Tests the validation process on IndexingWebContent.
 *
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
        $indexedDocument->setDocumentURI('http://test/nid/211');
        $indexedDocument->setDocumentContent('this is the content');

        $metadata = new StringMetadata('title');
        $metadata->setValues(['The content title']);
        $indexedDocument->addMetadata($metadata);

        $metadata = new IntegerMetadata('int_sets');
        $metadata->setValues([1, 2, 3]);
        $indexedDocument->addMetadata($metadata);

        $metadata = new DateMetadata('publishing_date');
        $metadata->setValues(['20-12-2018']);
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
        $expected = $parsedData['expectedViolations']['IndexingWebContent'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IndexedDocument validation constraints are not well defined for: '.$name);
        }
    }
}
