<?php

namespace EC\EuropaSearch\Tests\Proxies\Converters\Index;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class IndexFileConvertTest.
 *
 * Test the conversion of a IndexWebContentMessage object.
 *
 * @package EC\EuropaSearch\Tests\Proxies\Converters\Index
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

        $this->assertEquals($expected, $indexingRequest, 'The conversion of the IndexFileMessage object has failed.');
    }
}