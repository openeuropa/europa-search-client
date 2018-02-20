<?php

namespace OpenEuropa\EuropaSearch\Tests\Proxies\Converters\Index;

use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use OpenEuropa\EuropaSearch\Transporters\Requests\Index\IndexFileRequest;
use GuzzleHttp\Psr7;

/**
 * Class IndexFileConvertTest.
 *
 * Test the conversion of a IndexWebContentMessage object.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Proxies\Converters\Index
 */
class IndexFileConvertTest extends AbstractEuropaSearchTest
{

    /**
     * Test a conversion of an IndexedDocument object in a WebContentRequest one.
     */
    public function testConvertIndexFileSuccess()
    {
        $provider = new ConverterDataProvider();
        $data = $provider->indexedFileProvider();

        $submitted = $data['submitted'];
        $expected = $data['expected'];

        $proxy = $this->getContainer()->get('europaSearch.proxyController.default');
        $proxy->initProxy($this->getDummyIndexingAppConfig());
        $convertedComponents = $proxy->convertComponents($submitted->getComponents());

        $converterId = $submitted->getConverterIdentifier();
        $converter = $proxy->getConverterObject($converterId);
        $indexingRequest = $proxy->convertMessageWithComponents($converter, $submitted, $convertedComponents);

        $this->assertIndexFileRequestEquals($expected, $indexingRequest, 'The conversion of the IndexFileMessage object has failed.');
    }

    /**
     * Asserts 2 IndexFileRequest are equals.
     *
     * @param IndexFileRequest $expected
     *   The expected IndexFileRequest object.
     * @param IndexFileRequest $actual
     *   The actual IndexFileRequest object.
     * @param string $message
     *   The message to send back when object are not equals.
     */
    protected function assertIndexFileRequestEquals(IndexFileRequest $expected, IndexFileRequest $actual, $message = 'The conversion of the IndexFileMessage object has failed.')
    {
        // Simple clone call is enough for cloning IndexFileRequest objects.
        $expectedClone = clone $expected;
        $actualClone = clone $actual;

        $expectedStream = $expectedClone->getDocumentFile();
        $actualStream = $actualClone->getDocumentFile();

        // emptying the documentFile in order to compare the rest of objects easily.
        $expectedClone->setDocumentFile('');
        $actualClone->setDocumentFile('');

        // Tests the other object properties.
        $this->assertEquals($expectedClone, $actualClone, $message);

        // Assert the 2 resources stored in objects by compare their hash.
        $expectedHash = Psr7\hash($expectedStream, 'md5');
        $actualHash = Psr7\hash($actualStream, 'md5');

        $this->assertEquals($expectedHash, $actualHash, $message);
    }
}
