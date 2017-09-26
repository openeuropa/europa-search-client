<?php

/**
 * @file
 * Contains EC\EuropaSearch\Tests\Clients\IndexingClientTest.
 */

namespace EC\EuropaSearch\Clients;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use EC\EuropaSearch\Tests\Clients\ClientDataProvider;
use GuzzleHttp\Psr7\Response;

/**
 * Class IndexingClientTest.
 *
 * Tests the client layer related to the indexing process.
 *
 * @package EC\EuropaSearch\Tests\Clients
 */
class IndexingClientTest extends AbstractEuropaSearchTest
{

    /**
     * Test that the client process passes all its steps.
     */
    public function testIndexingClientProcessSuccess()
    {
        $provider = new ClientDataProvider();
        $indexingMessage = $provider->getWebContentMessageTestData();

        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $client = $factory->getIndexingWebContentClient();
        $response = $client->sendMessage($indexingMessage);

        $this->assertInstanceOf('EC\EuropaWS\Clients\DefaultClient', $client, 'The returned client is not an DefaultClient object.');
        $this->assertInstanceOf('EC\EuropaWS\Messages\StringResponseMessage', $response, 'The returned response is not an StringResponseMessage object.');
        $this->assertEquals('web_content_client_1', $response->getReturnedString(), 'The returned response is not the expected one.');
    }

    /**
     * Gets the web service mock responses for tests.
     *
     * @return array
     *   The web service mock responses.
     */
    private function getMockResponse()
    {
        $body = json_decode(file_get_contents(__DIR__.'/fixtures/index_response_sample.json'));
        $response = new Response(200, [], json_encode($body));
        $mockResponses = [$response];

        return $mockResponses;
    }
}
