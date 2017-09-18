<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Proxies\Index\IndexingWebContentConvertTest.
 */

namespace EC\EuropaSearch\Tests\Proxies\Index;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class IndexingWebContentConvertTest.
 *
 * Test the conversion of a IndexingWebContent object.
 *
 * @package EC\EuropaSearch\Tests\Proxies\Index
 */
class IndexingWebContentConvertTest extends AbstractEuropaSearchTest
{

    /**
     * Test a conversion of an IndexedDocument object in a IndexingRequest one.
     */
    public function testConvertIndexingWebContentSuccess()
    {

        $provider = new WebContentDataProvider();
        $data = $provider->indexedDocumentProvider();

        $submitted = $data['submitted'];
        $expected = $data['expected'];

        $proxy = $this->getContainer()->get('proxyController');
        $proxy->initProxy($this->getDummyConfig());
        $convertedComponents = $proxy->convertComponents($submitted->getComponents());

        $converterId = $submitted->getConverterIdentifier();
        $converter = $proxy->getConverterObject($converterId);
        $indexingRequest = $proxy->convertMessageWithComponents($converter, $submitted, $convertedComponents);

        $this->assertEquals($expected, $indexingRequest, 'The conversion of the IndexingWebContent object has failed.');
    }
}
