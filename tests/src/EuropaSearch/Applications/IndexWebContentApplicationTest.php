<?php

namespace OpenEuropa\EuropaSearch\Tests\Applications;

use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class IndexWebContentApplicationTest.
 *
 * Tests the client layer related to the indexing process.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Applications
 */
class IndexWebContentApplicationTest extends AbstractEuropaSearchTest
{

    /**
     * Test that the application process passes all its steps.
     *
     * It ensure also that the application is retrieved via the container with
     * the right configuration.
     */
    public function testApplicationProcessSuccess()
    {
        $provider = new ApplicationDataProvider();
        $indexingMessage = $provider->getWebContentMessageTestData();

        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $application = $factory->getIndexingApplication();
        $response = $application->sendMessage($indexingMessage);

        $this->assertInstanceOf('OpenEuropa\EuropaSearch\Applications\Application', $application, 'The returned application is not an Application object.');
        $this->assertInstanceOf('OpenEuropa\EuropaSearch\Messages\Index\IndexingResponse', $response, 'The returned response is not an IndexingResponse object.');
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
        $response = new Response(200, [], json_encode($body->contentScenario));
        $mockResponses = [$response];

        return $mockResponses;
    }
}
