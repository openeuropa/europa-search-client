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
use EC\EuropaSearch\Tests\Clients\ClientDataProvider;
use EC\EuropaSearch\Tests\Clients\WebContentDataProvider;
use EC\EuropaSearch\Tests\EuropaSearchDummy;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use EC\EuropaSearch\Tests\Proxies\Search\SearchDataProvider;
use GuzzleHttp\Psr7\Response;

/**
 * Class ClientDataProvider.
 *
 * Tests the client layer related to the searching/indexing process.
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
        $provider = new ClientDataProvider();
        $data = $provider->getSearchMessageTestData();

        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $client = $factory->getSearchingClient();

        $this->assertInstanceOf('EC\EuropaWS\Clients\DefaultClient', $client, 'The returned client is not an DefaultClient object.');

        $response = $client->sendMessage($data['submitted']);
        $this->assertEquals($data['expected'], $response, 'The returned response is not the expected one.');
    }

    /**
     * Gets the web service mock responses for tests.
     *
     * @return array
     *   The web service mock responses.
     */
    private function getMockResponse()
    {
        $body = file_get_contents(__DIR__.'/fixtures/search_response_sample.json');

        $response = new Response(200, [], $body);
        $mockResponses = [$response];

        return $mockResponses;
    }
}
