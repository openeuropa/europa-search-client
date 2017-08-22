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

        $proxy = $this->getContainer()->get('proxyProvider');

        $convertedComponents = $proxy->convertComponents($submitted->getComponents());
        $indexingRequest = $proxy->convertMessageWithComponents($submitted, $convertedComponents);

        $expected = json_encode($expected);
        $returned = json_encode($indexingRequest);

        $this->assertJsonStringEqualsJsonString($expected, $returned, 'The conversion of the IndexingWebContent object has failed.');
    }
}
