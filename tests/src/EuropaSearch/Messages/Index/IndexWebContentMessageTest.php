<?php

namespace OpenEuropa\EuropaSearch\Tests\Messages\Index;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\DateMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\IntegerMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\StringMetadata;
use OpenEuropa\EuropaSearch\Messages\Index\IndexWebContentMessage;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use Symfony\Component\Yaml\Yaml;

/**
 * Class IndexWebContentMessageTest.
 *
 * Tests the validation process on IndexWebContentMessage.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Messages\Index
 */
class IndexWebContentMessageTest extends AbstractEuropaSearchTest
{

    /**
     * Test the IndexWebContentMessage validation (success case).
     */
    public function testIndexWebContentMessageValidationSuccess()
    {
        $indexedDocument = new IndexWebContentMessage();
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

        $this->assertEmpty($violations, 'IndexWebContentMessage validation constraints are not well defined.');
    }

    /**
     * Test the IndexWebContentMessage validation (failure case).
     */
    public function testIndexWebContentMessageValidationFailure()
    {
        $indexedDocument = new IndexWebContentMessage();
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
        $expected = $parsedData['expectedViolations']['IndexWebContentMessage'];

        foreach ($violations as $name => $violation) {
            $this->assertEquals($violation, $expected[$name], 'IndexWebContentMessage validation constraints are not well defined for: '.$name);
        }
    }
}
