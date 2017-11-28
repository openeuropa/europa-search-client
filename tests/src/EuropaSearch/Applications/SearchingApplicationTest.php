<?php

namespace EC\EuropaSearch\Tests\Applications;

use EC\EuropaSearch\Tests\EuropaSearchDummy;
use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class SearchingApplicationTest.
 *
 * Tests the Application layer related to the searching/indexing process.
 *
 * @package EC\EuropaSearch\Tests\Applications
 */
class SearchingApplicationTest extends AbstractEuropaSearchTest
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
        $data = $provider->getSearchMessageTestData();

        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $application = $factory->getSearchApplication();
        $response = $application->sendMessage($data['submitted']);

        $this->assertInstanceOf('EC\EuropaSearch\Applications\Application', $application, 'The returned application is not an Application object.');
        $this->assertEquals($data['expected'], $response, 'The returned response is not the expected one.');
    }

    /**
     * Test that the application uses the right configuration.
     *
     * This configuration comes from the container.
     */
    public function testApplicationConfiguration()
    {
        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $application = $factory->getSearchApplication();
        $applicationConfig = $application->getApplicationConfiguration();

        $this->assertArraySubset(
            $this->getTestedSearchServiceParams(),
            $applicationConfig->getConnectionConfigurations(),
            'The returned search application does not have the expected configuration.'
        );
    }

    /**
     * Gets the web service mock responses for tests.
     *
     * @return array
     *   The web service mock responses.
     */
    private function getMockResponse()
    {
        $body = json_decode(file_get_contents(__DIR__.'/fixtures/search_response_sample.json'));
        $response = new Response(200, [], json_encode($body));
        $mockResponses = [$response];

        return $mockResponses;
    }
}
