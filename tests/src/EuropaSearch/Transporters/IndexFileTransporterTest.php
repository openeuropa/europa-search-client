<?php

namespace OpenEuropa\EuropaSearch\Tests\Transporters;

use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class IndexFileTransporterTest
 *
 * Test the transporter for a IndexFileRequest.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Transporters
 */
class IndexFileTransporterTest extends AbstractEuropaSearchTest
{

    /**
     * Test a sending of an IndexWebContentRequest object.
     */
    public function testSendIndexFileSuccess()
    {
        $provider = new TransporterDataProvider();
        $requestToSend = $provider->fileIndexingRequestProvider();

        $transporter = $this->getContainer()->get('europaSearch.transporter.default');
        $testConfig = $this->getMockConfiguration();

        $transporter->initTransporter($testConfig);
        $transporter->send($requestToSend);

        $history = $transporter->getTransactionHistory();
        $transaction = reset($history);
        $sentRequest = $transaction['request'];

        $this->assertEquals('POST', $sentRequest->getMethod(), 'The indexing request does not use the right HTTP method.');

        $expectedTarget = '/es/ingestion-api/rest/ingestion?apiKey=a221108a-180d-HTTP-INDEXING-TEST&database=EC-EUROPA-DUMMY-INDEXING&reference=web_content_1&uri=http%3A%2F%2Feuropa.test.com%2Ffile.pdf&language=fr';
        $this->assertEquals($expectedTarget, $sentRequest->getRequestTarget(), 'The indexing request does not use the right url.');

        $contentType = $sentRequest->getHeader('Content-Type');
        $this->assertStringStartsWith('multipart/form-data', reset($contentType), 'The indexing request does not use the "Content-type" header attribute.');
    }

    /**
     * Gets the web service mock configuration for tests.
     *
     * @return \OpenEuropa\EuropaSearch\EuropaSearchConfig
     *   The web service mock configuration.
     */
    private function getMockConfiguration()
    {
        $body = '{
            "apiVersion" : "2.1",
            "trackingId" : "9e30f972-54f0-4e7d-8f94-8dd214d2fea5",
            "reference" : "web_content_1"
        }';
        $response = new Response(200, [], $body);
        $mockResponses = [$response];

        return $this->getDummyIndexingAppConfig($mockResponses);
    }
}
