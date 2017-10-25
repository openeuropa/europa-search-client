<?php

namespace EC\EuropaSearch\Tests\Applications;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class IndexingApplicationTest.
 *
 * Tests the client layer related to the indexing process.
 *
 * @package EC\EuropaSearch\Tests\Applications
 */
class IndexingApplicationTest extends AbstractEuropaSearchTest
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
        $applicationConfig = $application->getApplicationConfiguration();
        $response = $application->sendMessage($indexingMessage);

        $this->assertArraySubset(
            $this->getTestedIndexingServiceParams(),
            $applicationConfig->getConnectionConfigurations(),
            'The returned indexing application does not have the expected configuration. '
        );
        $this->assertInstanceOf('EC\EuropaSearch\Applications\Application', $application, 'The returned application is not an Application object.');
        $this->assertInstanceOf('EC\EuropaSearch\Messages\StringResponseMessage', $response, 'The returned response is not an StringResponseMessage object.');
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
