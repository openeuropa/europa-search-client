<?php

namespace EC\EuropaSearch\Tests\Applications;

use EC\EuropaSearch\Tests\AbstractEuropaSearchTest;
use GuzzleHttp\Psr7\Response;

/**
 * Class IndexFileApplicationTest.
 *
 * Tests the client layer related to the file indexing process.
 *
 * @package EC\EuropaSearch\Tests\Applications
 */
class IndexFileApplicationTest extends AbstractEuropaSearchTest
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
        $indexingFile = $provider->getFileMessageTestData();

        $mockConfig = $this->getMockResponse();
        $factory = $this->getFactory($mockConfig);
        $application = $factory->getIndexingApplication();
        $response = $application->sendMessage($indexingFile);

        $this->assertInstanceOf('EC\EuropaSearch\Applications\Application', $application, 'The returned application is not an Application object.');
        $this->assertInstanceOf('EC\EuropaSearch\Messages\Index\IndexingResponse', $response, 'The returned response is not an IndexingResponse object.');
        $this->assertEquals('file_client_1', $response->getReturnedString(), 'The returned response is not the expected one.');
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
        $response = new Response(200, [], json_encode($body->fileScenario));
        $mockResponses = [$response];

        return $mockResponses;
    }
}
