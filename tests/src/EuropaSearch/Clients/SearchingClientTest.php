<?php

/**
 * @file
 * Contains EC\EuropaSearch\Clients\SearchingClientTest.
 */

namespace EC\EuropaSearch\Clients;

use EC\EuropaSearch\Messages\DocumentMetadata\DateMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FloatMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\FullTextMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\IntegerMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\URLMetadata;
use EC\EuropaSearch\Messages\Index\IndexingWebContent;
use EC\EuropaSearch\Tests\EuropaSearchDummy;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use EC\EuropaSearch\Tests\Proxies\Search\SearchDataProvider;

/**
 * Class IndexingClientTest.
 *
 * Tests the client layer related to the searching process.
 *
 * @package EC\EuropaSearch\Clients
 */
class SearchingClientTest extends AbstractEuropaSearchTest
{

    /**
     * Test that the client process passes all its steps.
     */
    public function testClientProcessSuccess()
    {
        $provider = new SearchDataProvider();
        $data = $provider->searchRequestProvider();

        $factory = new EuropaSearchDummy();
        $client = $factory->getSearchingClient();

        $this->assertInstanceOf('EC\EuropaWS\Clients\DefaultClient', $client, 'The returned client is not an DefaultClient object.');

        $response = $client->sendMessage($data['submitted']);

        $expectedResponse = 'Request received but I am a dumb transporter; I receive request but I do nothing else.';
        $this->assertEquals($response, $expectedResponse, 'The returned response is not the expected.');
    }
}
