<?php

namespace EC\EuropaSearch\Tests\Proxies\Converters\Index;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class IndexingWebContentConvertTest.
 *
 * Test the conversion of a IndexingWebContent object.
 *
 * @package EC\EuropaSearch\Tests\Proxies\Converters\Index
 */
class IndexingWebContentConvertTest extends AbstractEuropaSearchTest
{

    /**
     * Test a conversion of an IndexedDocument object in a WebContentRequest one.
     */
    public function testConvertIndexingWebContentSuccess()
    {
        $provider = new WebContentDataProvider();
        $data = $provider->indexedDocumentProvider();

        $submitted = $data['submitted'];
        $expected = $data['expected'];

        $proxy = $this->getContainer()->get('proxyController.default');
        $proxy->initProxy($this->getDummyIndexingAppConfig());
        $convertedComponents = $proxy->convertComponents($submitted->getComponents());

        $converterId = $submitted->getConverterIdentifier();
        $converter = $proxy->getConverterObject($converterId);
        $indexingRequest = $proxy->convertMessageWithComponents($converter, $submitted, $convertedComponents);

        $this->assertEquals($expected, $indexingRequest, 'The conversion of the IndexingWebContent object has failed.');
    }
}
