<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Proxies\Search\SearchMessageConverterTest.
 */

namespace EC\EuropaSearch\Tests\Proxies\Search;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;

/**
 * Class SearchMessageConverterTest.
 *
 * Test the conversion of a SearchMessage object.
 *
 * @package EC\EuropaSearch\Tests\Proxies\Search
 */
class SearchMessageConverterTest extends AbstractEuropaSearchTest
{

    /**
     * Test a conversion of an SearchMessage object in a SearchRequest one.
     */
    public function testConvertSearchMessageSuccess()
    {
        $provider = new SearchDataProvider();
        $data = $provider->searchRequestProvider();

        $submitted = $data['submitted'];
        $expected = $data['expected'];

        $proxy = $this->getContainer()->get('proxyController.search');

        $convertedComponents = $proxy->convertComponents($submitted->getComponents());
        $searchRequest = $proxy->convertMessageWithComponents($submitted, $convertedComponents);

        $expected = json_encode($expected);
        $returned = json_encode($searchRequest);

        $this->assertJsonStringEqualsJsonString($expected, $returned, 'The conversion of the SearchMessage object has failed.');
    }
}
