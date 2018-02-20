<?php

namespace OpenEuropa\EuropaSearch\Tests\Applications;

use OpenEuropa\EuropaSearch\Tests\EuropaSearchDummy;
use OpenEuropa\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class SearchingApplicationTest.
 *
 * Tests the Application layer related to the searching/indexing process.
 *
 * @package OpenEuropa\EuropaSearch\Tests\Applications
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

        $this->assertInstanceOf('OpenEuropa\EuropaSearch\Applications\Application', $application, 'The returned application is not an Application object.');
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
        $body = json_decode(file_get_contents(__DIR__.'/fixtures/search_response_sample.json'));
        $response = new Response(200, [], json_encode($body));
        $mockResponses = [$response];

        return $mockResponses;
    }
}
