<?php

namespace EC\EuropaSearch\Tests\Transporters;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class SearchTransporterTest.
 *
 * Test the transporter for the Search REST service (Search API).
 *
 * @package EC\EuropaSearch\Tests\Transporters
 */
class SearchTransporterTest extends AbstractEuropaSearchTest
{

    /**
     * Test a sending of an IndexingRequest object.
     */
    public function testSendSearchSuccess()
    {
        $provider = new WebContentDataProvider();
        $requestToSend = $provider->searchRequestProvider();

        $transporter = $this->getContainer()->get('europaSearch.transporter.default');
        $testConfig = $this->getMockConfiguration();

        $transporter->initTransporter($testConfig);
        $transporter->send($requestToSend);

        $history = $transporter->getTransactionHistory();
        $transaction = reset($history);
        $sentRequest = $transaction['request'];

        $this->assertEquals('POST', $sentRequest->getMethod(), 'The search request does not have the right HTTP method.');

        $expectedTarget = '/rest/search?highlightRegex=%3Cstrong%3E%7B%7D%3C%2Fstrong%3E&highlightLimit=250&pageSize=20&pageNumber=1&sessionToken=123456&apiKey=a221108a-180d-HTTP-SEARCH-TEST';
        $this->assertEquals($expectedTarget, $sentRequest->getRequestTarget(), 'The search request does not have the right url.');

        $contentType = $sentRequest->getHeader('Content-Type');
        $this->assertStringStartsWith('multipart/form-data', reset($contentType), 'The search request does not have the "Content-type" header attribute.');
    }

    /**
     * Gets the web service mock configuration for tests.
     *
     * @return \EC\EuropaSearch\EuropaSearchConfig
     *   The web service mock configuration.
     */
    private function getMockConfiguration()
    {
        $body = '{}';
        $response = new Response(201, [], $body);
        $mockResponses = [$response];

        return $this->getDummySearchAppConfig($mockResponses);
    }
}
