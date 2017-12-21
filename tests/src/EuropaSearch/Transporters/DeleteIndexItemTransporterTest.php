<?php

namespace EC\EuropaSearch\Tests\Transporters;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class DeleteIndexItemTransporterTest
 *
 * Test the transporter for a IndexWebContentRequest.
 *
 * @package EC\EuropaSearch\Tests\Transporters
 */
class DeleteIndexItemTransporterTest extends AbstractEuropaSearchTest
{

    /**
     * Test a sending of an IndexWebContentRequest object.
     */
    public function testSendIndexItemDeletionSuccess()
    {
        $provider = new TransporterDataProvider();
        $requestToSend = $provider->deleteIndexItemTestData();

        $transporter = $this->getContainer()->get('europaSearch.transporter.default');
        $testConfig = $this->getMockConfiguration();

        $transporter->initTransporter($testConfig);
        $transporter->send($requestToSend);

        $history = $transporter->getTransactionHistory();
        $transaction = reset($history);
        $sentRequest = $transaction['request'];

        $this->assertEquals('DELETE', $sentRequest->getMethod(), 'The indexing request does not use the right HTTP method.');

        $expectedTarget = '/es/ingestion-api/rest/ingestion?reference=web_content_delete_1&apiKey=a221108a-180d-HTTP-INDEXING-TEST&database=EC-EUROPA-DUMMY-INDEXING';
        $this->assertEquals($expectedTarget, $sentRequest->getRequestTarget(), 'The index item deletion request does not use the right url.');
    }

    /**
     * Gets the web service mock configuration for tests.
     *
     * @return \EC\EuropaSearch\EuropaSearchConfig
     *   The web service mock configuration.
     */
    private function getMockConfiguration()
    {
        $response = new Response(200, [], 'true');
        $mockResponses = [$response];

        return $this->getDummyIndexingAppConfig($mockResponses);
    }
}
