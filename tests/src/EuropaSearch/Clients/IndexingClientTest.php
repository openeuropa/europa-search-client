<?php

/**
 * @file
 * Contains EC\EuropaSearch\Clients\IndexingClientTest.
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
use EC\EuropaSearch\Tests\Proxies\Index\WebContentDataProvider;
use GuzzleHttp\Psr7\Response;

/**
 * Class IndexingClientTest.
 *
 * Tests the client layer related to the indexing process.
 *
 * @package EC\EuropaSearch\Clients
 */
class IndexingClientTest extends AbstractEuropaSearchTest
{

    /**
     * Test that the client process passes all its steps.
     */
    public function testIndexingClientProcessSuccess()
    {

        $provider = new WebContentDataProvider();
        $data = $provider->indexedDocumentProvider();

        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $client = $factory->getIndexingWebContentClient();

        $this->assertInstanceOf('EC\EuropaWS\Clients\DefaultClient', $client, 'The returned client is not an DefaultClient object.');

        $response = $client->sendMessage($data['submitted']);

        $this->assertInstanceOf('EC\EuropaWS\Messages\StringResponseMessage', $response, 'The returned response is not an StringResponseMessage object.');

        $this->assertEquals($response->getReturnedString(), 'web_content_client_1', 'The returned response is not the expected.');
    }

    /**
     * Gets the web service mock responses for tests.
     *
     * @return array
     *   The web service mock responses.
     */
    private function getMockResponse()
    {

        $body = '{
            "apiVersion" : "2.1",
            "trackingId" : "9e30f972-54f0-4e7d-8f94-8dd214d2fea5",
            "reference" : "web_content_client_1"
        }';
        $response = new Response(200, [], $body);
        $mockResponses = [$response];

        return $mockResponses;
    }
}
