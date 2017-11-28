<?php

namespace EC\EuropaSearch\Tests\Applications;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class IndexWebContentApplicationTest.
 *
 * Tests the client layer related to the indexing process.
 *
 * @package EC\EuropaSearch\Tests\Applications
 */
class IndexItemDeletionApplicationTest extends AbstractEuropaSearchTest
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
        $indexingMessage = $provider->getDeleteIndexItemTestData();

        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $application = $factory->getIndexingApplication();
        $response = $application->sendMessage($indexingMessage);

        $this->assertInstanceOf('EC\EuropaSearch\Applications\Application', $application, 'The returned application is not an Application object.');
        $this->assertInstanceOf('EC\EuropaSearch\Messages\Index\IndexingResponse', $response, 'The returned response is not an IndexingResponse object.');
        $this->assertEquals('web_content_delete_1', $response->getReturnedString(), 'The returned response is not the expected one.');
    }

    /**
     * Gets the web service mock responses for tests.
     *
     * @return array
     *   The web service mock responses.
     */
    private function getMockResponse()
    {
        $response = new Response(200, [], 'true');
        $mockResponses = [$response];

        return $mockResponses;
    }
}
