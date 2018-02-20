<?php

namespace OpenEuropa\EuropaSearch\Tests\Proxies\Converters\Index;

use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class IndexWebContentConvertTest.
 *
 * Test the conversion of a IndexWebContentMessage object.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Proxies\Converters\Index
 */
class IndexWebContentConvertTest extends AbstractEuropaSearchTest
{

    /**
     * Test a conversion of an IndexedDocument object in a WebContentRequest one.
     */
    public function testConvertIndexWebContentSuccess()
    {
        $provider = new ConverterDataProvider();
        $data = $provider->indexedDocumentProvider();

        $submitted = $data['submitted'];
        $expected = $data['expected'];

        $proxy = $this->getContainer()->get('europaSearch.proxyController.default');
        $proxy->initProxy($this->getDummyIndexingAppConfig());
        $convertedComponents = $proxy->convertComponents($submitted->getComponents());

        $converterId = $submitted->getConverterIdentifier();
        $converter = $proxy->getConverterObject($converterId);
        $indexingRequest = $proxy->convertMessageWithComponents($converter, $submitted, $convertedComponents);

        $this->assertEquals($expected, $indexingRequest, 'The conversion of the IndexWebContent object has failed.');
    }
}
